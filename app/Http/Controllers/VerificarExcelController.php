<?php

namespace App\Http\Controllers;

use App\Exports\VerificacaoExport;
use App\Models\BeneficiarioUrbano;
use App\Services\VerificacaoExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class VerificarExcelController extends Controller
{
    public function __construct(protected VerificacaoExcelService $servico)
    {
    }

    /**
     * Página com o formulário de upload
     */
    public function index()
    {
        return view('verificar.excel', ['linhas' => null]);
    }

    /**
     * Lê o Excel, verifica duplicados e guarda o resultado (2 horas)
     */
    public function processar(Request $request)
    {
        $request->validate([
            'ficheiro' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ], [
            'ficheiro.required' => 'Escolhe um ficheiro Excel.',
            'ficheiro.mimes'    => 'O ficheiro tem de ser .xlsx, .xls ou .csv.',
            'ficheiro.max'      => 'O ficheiro não pode ter mais de 10 MB.',
        ]);

        $ficheiro = $request->file('ficheiro');

        try {
            $linhas    = $this->servico->lerFicheiro($ficheiro->getRealPath());
            $resultado = $this->servico->verificar($linhas);
        } catch (\Throwable $e) {
            return back()->with('error', '❌ ' . $e->getMessage());
        }

        $token = (string) Str::uuid();

        Cache::put("verificacao:{$token}", [
            'nome'   => $ficheiro->getClientOriginalName(),
            'linhas' => $resultado,
        ], now()->addHours(2));

        return redirect()->route('verificar.resultado', $token);
    }

    /**
     * Mostra o resultado da verificação
     */
    public function resultado(string $token)
    {
        $dados = Cache::get("verificacao:{$token}");

        if (!$dados) {
            return redirect()->route('verificar.index')
                ->with('error', '⚠️ O resultado expirou. Carrega o ficheiro outra vez.');
        }

        $linhas = $dados['linhas'];
        $col    = collect($linhas);

        return view('verificar.excel', [
            'token'        => $token,
            'nomeFicheiro' => $dados['nome'],
            'linhas'       => $linhas,
            'total'        => count($linhas),
            'novos'        => $col->where('status', 'novo')->count(),
            'possiveis'    => $col->where('status', 'possivel')->count(),
            'duplicados'   => $col->where('status', 'duplicado')->count(),
        ]);
    }

    /**
     * Exporta o resultado (com a coluna Status) para Excel
     */
    public function exportar(string $token)
    {
        $dados = Cache::get("verificacao:{$token}");

        if (!$dados) {
            return redirect()->route('verificar.index')
                ->with('error', '⚠️ O resultado expirou. Carrega o ficheiro outra vez.');
        }

        $filename = 'Verificacao_Duplicados_' . now()->format('d-m-Y_H-i') . '.xlsx';

        return Excel::download(new VerificacaoExport($dados['linhas']), $filename);
    }

    /**
     * Importa para o Kwenda Urbano apenas as linhas com status "novo"
     */
    public function importar(string $token)
    {
        $dados = Cache::get("verificacao:{$token}");

        if (!$dados) {
            return redirect()->route('verificar.index')
                ->with('error', '⚠️ O resultado expirou. Carrega o ficheiro outra vez.');
        }

        $novos      = collect($dados['linhas'])->where('status', 'novo');
        $importados = 0;

        DB::beginTransaction();
        try {
            foreach ($novos as $l) {
                $obs = collect([
                    'Importado via Excel',
                    !empty($l['zona']) ? 'Zona: ' . $l['zona'] : null,
                ])->filter()->implode(' | ');

                // forceFill: não depende do $fillable do modelo
                $beneficiario = new BeneficiarioUrbano();
                $beneficiario->forceFill([
                    'nome'            => $l['nome'],
                    'sexo'            => $l['sexo'],
                    'data_nascimento' => $l['data_nascimento'],
                    'telefone'        => $l['telefone'],
                    'bairro'          => $l['bairro'],
                    'categoria'       => $l['categoria'],
                    'data_inscricao'  => now()->format('Y-m-d'),
                    'pago'            => 'nao',
                    'obs'             => $obs,
                ])->save();

                $importados++;
            }

            DB::commit();

            // Evita importar duas vezes o mesmo resultado
            Cache::forget("verificacao:{$token}");

            return redirect()->route('verificar.index')->with('success',
                "✅ {$importados} novos beneficiários importados para o Urbano."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ Erro ao importar: ' . $e->getMessage());
        }
    }
}