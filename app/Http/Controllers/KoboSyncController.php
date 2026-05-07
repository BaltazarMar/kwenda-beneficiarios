<?php

namespace App\Http\Controllers;

use App\Services\KoboToolboxService;
use App\Models\Beneficiario;
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

        // Verifica duplicado confirmado na base de dados
        $statusBD = $this->verificarDuplicado($sub);
        if ($statusBD === 'duplicado') {
            return array_merge($sub, ['status' => 'duplicado']);
        }

        // Chave Nome + Data
        $chaveNomeData = strtolower(trim($sub['nome'] ?? '')) . '|' . ($sub['data_nascimento'] ?? '');

        // Se o mesmo Nome + Data aparece mais de uma vez nas submissões → Duplicado
        if ($contagemNomeData->get($chaveNomeData, 0) > 1) {
            return array_merge($sub, ['status' => 'duplicado']);
        }

        // Se apenas o Nome aparece mais de uma vez (datas diferentes) → Possível
        $chaveNome = strtolower(trim($sub['nome'] ?? ''));
        if ($contagemNome->get($chaveNome, 0) > 1) {
            return array_merge($sub, ['status' => 'possivel']);
        }

        return array_merge($sub, ['status' => 'novo']);
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
     * Importa apenas os beneficiários novos (sem duplicados)
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

                if (!empty($sub['kobo_id']) && Beneficiario::where('kobo_id', $sub['kobo_id'])->exists()) {
                    $ignorados++;
                    continue;
                }

                Beneficiario::create([
                    'kobo_id'               => $sub['kobo_id'],
                    'nome'                  => $sub['nome'],
                    'municipio'             => $sub['municipio'],
                    'bairro'                => $sub['bairro'],
                    'documento'             => $sub['documento'],
                    'data_nasc'             => $sub['data_nascimento'],
                    'genero'                => $sub['genero'],
                    'contacto'              => $sub['telefone'],
                    'categoria'             => $sub['categoria'],
                    'tecnico'               => $sub['tecnico'],
                    'instituicao'           => $sub['instituicao'],
                    'municipio_instituicao' => $sub['municipio_instituicao'],
                    'data_submissao'        => $sub['data_submissao'],
                    'origem'                => 'kobo',
                ]);

                $importados++;
            }

            DB::commit();

            return redirect()->route('kobo.sync')->with('success',
                "✅ Importação concluída! {$importados} novos beneficiários importados. {$ignorados} duplicados ignorados."
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
        $tipo        = $request->input('tipo', 'duplicado'); // 'duplicado', 'possivel', 'ambos'
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
            $sub['status'] = $this->verificarDuplicado($sub);
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
     * Verifica duplicados em 3 níveis
     */

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

    private function verificarDuplicado(array $sub): string
    {
        if (empty($sub['nome'])) {
            return 'novo';
        }

        if (!empty($sub['data_nascimento'])) {
            $duplicadoConfirmado = Beneficiario::whereRaw(
                'LOWER(TRIM(nome)) = LOWER(TRIM(?))', [trim($sub['nome'])]
            )
            ->where('data_nasc', $sub['data_nascimento'])
            ->exists();

            if ($duplicadoConfirmado) {
                return 'duplicado';
            }
        }

        $possivel = Beneficiario::whereRaw(
            'LOWER(TRIM(nome)) = LOWER(TRIM(?))', [trim($sub['nome'])]
        )->exists();

        if ($possivel) {
            return 'possivel';
        }

        return 'novo';
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