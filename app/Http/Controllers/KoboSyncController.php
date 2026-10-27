<?php

namespace App\Http\Controllers;

use App\Services\KoboToolboxService;
use App\Models\Beneficiario;
use App\Models\BeneficiarioUrbano;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class KoboSyncController extends Controller
{
    protected KoboToolboxService $kobo;

    public function __construct(KoboToolboxService $kobo)
    {
        $this->kobo = $kobo;
    }

    /**
     * Página de sincronização — mostra os dados do KoBoToolbox
     */
    public function index()
    {
        $submissions = $this->kobo->getFormattedSubmissions();
        $total       = count($submissions);

        // Agrupa por Nome + Data para detectar duplicados dentro das submissões
        $contagemNomeData = collect($submissions)
            ->groupBy(fn($sub) => strtolower(trim($sub['nome'] ?? '')) . '|' . ($sub['data_nascimento'] ?? ''))
            ->map(fn($grupo) => count($grupo));

        // Agrupa apenas por Nome para detectar possíveis duplicados
        $contagemNome = collect($submissions)
            ->groupBy(fn($sub) => strtolower(trim($sub['nome'] ?? '')))
            ->map(fn($grupo) => count($grupo));

        $submissionsComStatus = collect($submissions)->map(function ($sub) use ($contagemNomeData, $contagemNome) {

            // Verifica duplicado confirmado nas bases de dados (Urbano + Rural)
            [$statusBD, $onde] = $this->localizarDuplicado($sub);
            if ($statusBD === 'duplicado') {
                return array_merge($sub, ['status' => 'duplicado', 'encontrado_em' => $onde]);
            }

            // Chave Nome + Data
            $chaveNomeData = strtolower(trim($sub['nome'] ?? '')) . '|' . ($sub['data_nascimento'] ?? '');

            // Se o mesmo Nome + Data aparece mais de uma vez nas submissões → Duplicado
            if ($contagemNomeData->get($chaveNomeData, 0) > 1) {
                return array_merge($sub, ['status' => 'duplicado', 'encontrado_em' => 'Kobo']);
            }

            // Possível duplicado na base de dados (só o nome coincide)
            if ($statusBD === 'possivel') {
                return array_merge($sub, ['status' => 'possivel', 'encontrado_em' => $onde]);
            }

            // Se apenas o Nome aparece mais de uma vez (datas diferentes) → Possível
            $chaveNome = strtolower(trim($sub['nome'] ?? ''));
            if ($contagemNome->get($chaveNome, 0) > 1) {
                return array_merge($sub, ['status' => 'possivel', 'encontrado_em' => 'Kobo']);
            }

            return array_merge($sub, ['status' => 'novo', 'encontrado_em' => null]);
        })->toArray();

        $novos      = collect($submissionsComStatus)->where('status', 'novo')->count();
        $duplicados = collect($submissionsComStatus)->where('status', 'duplicado')->count();
        $possiveis  = collect($submissionsComStatus)->where('status', 'possivel')->count();

        return view('kobo.sync', compact(
            'submissionsComStatus',
            'total',
            'novos',
            'duplicados',
            'possiveis'
        ));
    }

    /**
     * Importa apenas os beneficiários novos para o Kwenda Urbano
     */
    public function importar(Request $request)
    {
        $submissions = $this->kobo->getFormattedSubmissions();
        $importados  = 0;
        $ignorados   = 0;

        DB::beginTransaction();
        try {
            foreach ($submissions as $sub) {
                if ($this->verificarDuplicado($sub) === 'duplicado') {
                    $ignorados++;
                    continue;
                }

                // Observação com os dados do Kobo que o urbano não tem em colunas próprias
                $obs = collect([
                    'Kobo',
                    !empty($sub['tecnico'])     ? 'Técnico: ' . $sub['tecnico']         : null,
                    !empty($sub['instituicao']) ? 'Instituição: ' . $sub['instituicao'] : null,
                ])->filter()->implode(' | ');

                $dataInscricao = null;
                if (!empty($sub['data_submissao'])) {
                    try {
                        $dataInscricao = Carbon::parse($sub['data_submissao'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $dataInscricao = null;
                    }
                }

                // forceFill: não depende do $fillable do modelo
                $beneficiario = new BeneficiarioUrbano();
                $beneficiario->forceFill([
                    'nome'             => $sub['nome'] ?? null,
                    'sexo'             => $sub['genero'] ?? null,
                    'data_nascimento'  => $sub['data_nascimento'] ?? null,
                    'numero_documento' => !empty($sub['documento']) ? trim($sub['documento']) : null,
                    'telefone'         => $sub['telefone'] ?? null,
                    'municipio'        => $sub['municipio'] ?? null,
                    'bairro'           => $sub['bairro'] ?? null,
                    'categoria'        => $sub['categoria'] ?? null,
                    'data_inscricao'   => $dataInscricao,
                    'pago'             => 'nao',
                    'obs'              => $obs,
                ])->save();

                $importados++;
            }

            DB::commit();

            return redirect()->route('kobo.sync')->with('success',
                "✅ Importação concluída! {$importados} novos beneficiários importados para o Urbano. {$ignorados} duplicados ignorados."
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kobo.sync')->with('error',
                '❌ Erro ao importar: ' . $e->getMessage()
            );
        }
    }

    /**
     * Elimina submissões duplicadas e/ou possíveis duplicadas no KoBoToolbox
     */
    public function eliminarDuplicados(Request $request)
    {
        $tipo        = $request->input('tipo', 'duplicado');
        $submissions = $this->kobo->getFormattedSubmissions();
        $eliminados  = 0;
        $erros       = 0;

        $baseUrl  = config('kobotoolbox.base_url');
        $token    = config('kobotoolbox.api_token');
        $assetUid = config('kobotoolbox.asset_uid');

        foreach ($submissions as $sub) {
            $status = $this->verificarDuplicado($sub);

            $deveEliminar = match($tipo) {
                'duplicado' => $status === 'duplicado',
                'possivel'  => $status === 'possivel',
                'ambos'     => in_array($status, ['duplicado', 'possivel']),
                default     => false,
            };

            if ($deveEliminar && !empty($sub['kobo_id'])) {
                try {
                    $response = Http::timeout(30)->withHeaders([
                        'Authorization' => "Token {$token}",
                    ])->delete("{$baseUrl}/api/v2/assets/{$assetUid}/data/{$sub['kobo_id']}/");

                    if ($response->successful() || $response->status() === 204) {
                        $eliminados++;
                    } else {
                        $erros++;
                    }
                } catch (\Exception $e) {
                    $erros++;
                }
            }
        }

        $tipoLabel = match($tipo) {
            'duplicado' => 'duplicados confirmados',
            'possivel'  => 'possíveis duplicados',
            'ambos'     => 'duplicados e possíveis duplicados',
            default     => 'registos',
        };

        $mensagem = "🗑️ {$eliminados} {$tipoLabel} eliminados do KoBoToolbox.";
        if ($erros > 0) {
            $mensagem .= " ⚠️ {$erros} não foi possível eliminar.";
        }

        return redirect()->route('kobo.sync')->with('success', $mensagem);
    }

    /**
     * Retorna dados em JSON para actualização em tempo real (AJAX)
     */
    public function json()
    {
        $submissions = $this->kobo->getFormattedSubmissions();

        $submissionsComStatus = collect($submissions)->map(function ($sub) {
            [$status, $onde] = $this->localizarDuplicado($sub);
            $sub['status']        = $status;
            $sub['encontrado_em'] = $onde;
            return $sub;
        })->toArray();

        return response()->json([
            'total'      => count($submissionsComStatus),
            'novos'      => collect($submissionsComStatus)->where('status', 'novo')->count(),
            'duplicados' => collect($submissionsComStatus)->where('status', 'duplicado')->count(),
            'possiveis'  => collect($submissionsComStatus)->where('status', 'possivel')->count(),
            'dados'      => $submissionsComStatus,
        ]);
    }

    /**
     * Exporta as submissões do KoBoToolbox para Excel
     */
    public function exportarExcel()
    {
        $submissions = $this->kobo->getFormattedSubmissions();

        $submissionsComStatus = collect($submissions)->map(function ($sub) {
            $sub['status'] = $this->verificarDuplicado($sub);
            return $sub;
        })->toArray();

        $export   = new \App\Exports\KoboSubmissionsExport($submissionsComStatus);
        $filename = 'Kwenda_Urbano_Lunda_Sul_' . now()->format('d-m-Y') . '.xlsx';

        return $export->download($filename);
    }

    /**
     * Devolve apenas o status: 'novo', 'possivel' ou 'duplicado'
     */
    private function verificarDuplicado(array $sub): string
    {
        return $this->localizarDuplicado($sub)[0];
    }

    /**
     * Compara a submissão com o Kwenda Urbano e com o Kwenda Rural.
     * Devolve [status, onde] — onde = 'Urbano', 'Rural' ou null.
     *
     * 1. Nº de documento igual (só existe no Urbano)  → duplicado
     * 2. Nome + data de nascimento iguais (Urbano ou Rural) → duplicado
     * 3. Apenas nome igual (Urbano ou Rural) → possível
     */
    private function localizarDuplicado(array $sub): array
    {
        $nome = trim($sub['nome'] ?? '');
        if ($nome === '') {
            return ['novo', null];
        }

        $documento = trim((string) ($sub['documento'] ?? ''));
        $data      = $sub['data_nascimento'] ?? null;

        // 1. Documento — Urbano (o Rural não tem esta coluna)
        if ($documento !== '' && BeneficiarioUrbano::where('numero_documento', $documento)->exists()) {
            return ['duplicado', 'Urbano'];
        }

        // 2. Nome + data de nascimento
        if (!empty($data)) {
            $urbanoNomeData = BeneficiarioUrbano::whereRaw('LOWER(TRIM(nome)) = LOWER(TRIM(?))', [$nome])
                ->where('data_nascimento', $data)
                ->exists();
            if ($urbanoNomeData) {
                return ['duplicado', 'Urbano'];
            }

            $ruralNomeData = Beneficiario::whereRaw('LOWER(TRIM(nome)) = LOWER(TRIM(?))', [$nome])
                ->where('data_nasc', $data)
                ->exists();
            if ($ruralNomeData) {
                // Para tratar o Rural só como aviso, troca 'duplicado' por 'possivel'
                return ['duplicado', 'Rural'];
            }
        }

        // 3. Apenas nome
        if (BeneficiarioUrbano::whereRaw('LOWER(TRIM(nome)) = LOWER(TRIM(?))', [$nome])->exists()) {
            return ['possivel', 'Urbano'];
        }

        if (Beneficiario::whereRaw('LOWER(TRIM(nome)) = LOWER(TRIM(?))', [$nome])->exists()) {
            return ['possivel', 'Rural'];
        }

        return ['novo', null];
    }

    public function eliminarIndividual(Request $request)
    {
        $koboId   = $request->input('kobo_id');
        $baseUrl  = config('kobotoolbox.base_url');
        $token    = config('kobotoolbox.api_token');
        $assetUid = config('kobotoolbox.asset_uid');

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => "Token {$token}",
            ])->delete("{$baseUrl}/api/v2/assets/{$assetUid}/data/{$koboId}/");

            if ($response->successful() || $response->status() === 204) {
                return redirect()->route('kobo.sync')->with('success', '🗑️ Registo eliminado com sucesso!');
            }

            return redirect()->route('kobo.sync')->with('error', '❌ Não foi possível eliminar o registo.');
        } catch (\Exception $e) {
            return redirect()->route('kobo.sync')->with('error', '❌ Erro: ' . $e->getMessage());
        }
    }

    /**
     * Elimina TODOS os registos do KoBoToolbox
     */
    public function limparTodos()
    {
        $submissions = $this->kobo->getFormattedSubmissions();
        $eliminados  = 0;
        $erros       = 0;

        $baseUrl  = config('kobotoolbox.base_url');
        $token    = config('kobotoolbox.api_token');
        $assetUid = config('kobotoolbox.asset_uid');

        foreach ($submissions as $sub) {
            if (!empty($sub['kobo_id'])) {
                try {
                    $response = Http::timeout(30)->withHeaders([
                        'Authorization' => "Token {$token}",
                    ])->delete("{$baseUrl}/api/v2/assets/{$assetUid}/data/{$sub['kobo_id']}/");

                    if ($response->successful() || $response->status() === 204) {
                        $eliminados++;
                    } else {
                        $erros++;
                    }
                } catch (\Exception $e) {
                    $erros++;
                }
            }
        }

        $mensagem = "🗑️ {$eliminados} registos eliminados do KoBoToolbox.";
        if ($erros > 0) {
            $mensagem .= " ⚠️ {$erros} não foi possível eliminar.";
        }

        return redirect()->route('kobo.sync')->with('success', $mensagem);
    }
}