<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use Illuminate\Http\Request;
use App\Imports\BeneficiariosImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BeneficiariosExport;

class BeneficiarioController extends Controller
{
    // ================= BENEFICIÁRIOS =================
    public function index(Request $request)
{
    $query = Beneficiario::query();

    if ($request->filled('nome')) {
        $query->where('nome', 'like', '%' . $request->nome . '%');
    }

    if ($request->filled('social_id')) {
        $query->where('social_id', $request->social_id);
    }

    if ($request->filled('municipio')) {
        $query->where('municipio', $request->municipio);
    }

    if ($request->filled('bairro')) {
        $query->where('bairro', $request->bairro);
    }

    if ($request->filled('pago') && $request->pago !== '') {
        $query->where('pago', $request->pago);
    }

    if ($request->filled('sexo')) {
        $query->where('sexo', $request->sexo);
    }

    $perPage = in_array($request->per_page, [25, 50, 100]) ? $request->per_page : 25;
    $beneficiarios = $query->orderBy('nome')->paginate($perPage)->withQueryString();

    $municipios = Beneficiario::select('municipio')
        ->distinct()
        ->orderBy('municipio')
        ->pluck('municipio');

    $bairros = Beneficiario::select('bairro')
        ->whereNotNull('bairro')
        ->distinct()
        ->orderBy('bairro')
        ->pluck('bairro');

    return view('kwenda.beneficiarios.index', compact('beneficiarios', 'municipios', 'bairros'));
}
    //ff

    public function bairrosPorMunicipio(Request $request)
{
    $municipio = $request->municipio;

    $bairros = Beneficiario::select('bairro')
        ->whereNotNull('bairro')
        ->when($municipio, function($q) use ($municipio) {
            $q->where('municipio', $municipio);
        })
        ->distinct()
        ->orderBy('bairro')
        ->pluck('bairro');

    return response()->json($bairros);
}

public function exportar(Request $request)
{
    $filtros = $request->only(['nome', 'social_id', 'municipio', 'bairro', 'pago', 'sexo']);

    $nomeArquivo = 'beneficiarios_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\BeneficiariosExport($filtros),
        $nomeArquivo
    );
}

    public function create() {}
    public function store(Request $request) {}

    public function show(Beneficiario $beneficiario)
    {
        return view('kwenda.beneficiarios.show', compact('beneficiario'));
    }

    public function edit(Beneficiario $beneficiario)
    {
        return view('kwenda.beneficiarios.edit', compact('beneficiario'));
    }

    public function update(Request $request, Beneficiario $beneficiario)
    {
        $request->validate([
            'nome'        => 'nullable|string|max:255',
            'sexo'        => 'nullable|in:M,F',
            'data_nasc'   => 'nullable|date',
            'profissao'   => 'nullable|string|max:255',
            'provincia'   => 'nullable|string|max:255',
            'municipio'   => 'nullable|string|max:255',
            'comuna'      => 'nullable|string|max:255',
            'bairro'      => 'nullable|string|max:255',
            'contacto'    => 'nullable|string|max:255',
            'card_id'     => 'nullable|string|max:255',
            'agente'      => 'nullable|string|max:255',
            'pago'        => 'nullable|in:0,1,2',
            'observacoes' => 'nullable|string',
        ]);

        $beneficiario->update($request->all());

        return redirect()->route('beneficiarios.show', $beneficiario)
            ->with('success', 'Beneficiário actualizado com sucesso!');
    }

    public function destroy(Beneficiario $beneficiario) {}

    // ================= DASHBOARD =================
    public function dashboard()
    {
        $total      = Beneficiario::count();
        $pagos      = Beneficiario::where('pago', 1)->count();
        $naoPagos   = Beneficiario::where('pago', 0)->count();
        $nuncaPagos = Beneficiario::where('pago', 2)->count();
        $masculino  = Beneficiario::where('sexo', 'M')->count();
        $feminino   = Beneficiario::where('sexo', 'F')->count();

        $valorTotal = Beneficiario::selectRaw('
            SUM(rec1 + rec2 + rec3 + rec4 + rec5 + rec6) as total
        ')->value('total');

        $porMunicipio = Beneficiario::selectRaw('municipio, COUNT(*) as total')
            ->groupBy('municipio')
            ->orderByDesc('total')
            ->pluck('total', 'municipio');

        $bairros = Beneficiario::select('bairro')
            ->whereNotNull('bairro')
            ->distinct()
            ->count();

        return view('kwenda.dashboard', compact(
            'total',
            'pagos',
            'naoPagos',
            'nuncaPagos',
            'masculino',
            'feminino',
            'valorTotal',
            'porMunicipio',
            'bairros'
        ));
    }

    // ================= DASHBOARD FILTROS (AJAX) =================
    public function dashboardFiltros(Request $request)
    {
        $municipio = $request->municipio;
        $ano       = $request->ano;

        $query = Beneficiario::query();

        if ($municipio) {
            $query->where('municipio', $municipio);
        }

        if ($ano) {
            $query->where(function($q) use ($ano) {
                for ($i = 1; $i <= 6; $i++) {
                    $q->orWhereYear('data' . $i, $ano);
                }
            });
        }

        $total      = $query->count();
        $pagos      = (clone $query)->where('pago', 1)->count();
        $naoPagos   = (clone $query)->where('pago', 0)->count();
        $nuncaPagos = (clone $query)->where('pago', 2)->count();
        $masculino  = (clone $query)->where('sexo', 'M')->count();
        $feminino   = (clone $query)->where('sexo', 'F')->count();

        $valorTotal = (clone $query)->selectRaw('
            SUM(rec1 + rec2 + rec3 + rec4 + rec5 + rec6) as total
        ')->value('total');

        $bairros = (clone $query)->whereNotNull('bairro')
            ->distinct('bairro')
            ->count('bairro');

        $porMunicipio = Beneficiario::query()
            ->when($ano, function($q) use ($ano) {
                $q->where(function($q2) use ($ano) {
                    for ($i = 1; $i <= 6; $i++) {
                        $q2->orWhereYear('data' . $i, $ano);
                    }
                });
            })
            ->when($municipio, function($q) use ($municipio) {
                $q->where('municipio', $municipio);
            })
            ->selectRaw('municipio, COUNT(*) as total')
            ->groupBy('municipio')
            ->orderByDesc('total')
            ->pluck('total', 'municipio');

        return response()->json([
            'total'        => $total,
            'pagos'        => $pagos,
            'naoPagos'     => $naoPagos,
            'nuncaPagos'   => $nuncaPagos,
            'masculino'    => $masculino,
            'feminino'     => $feminino,
            'valorTotal'   => $valorTotal,
            'porMunicipio' => $porMunicipio,
            'bairros'      => $bairros,
        ]);
    }

    // ================= RECORRÊNCIAS (AJAX) =================
    public function recorrenciasMunicipio(Request $request)
    {
        $municipio = $request->municipio;
        $ano       = $request->ano;

        $query = Beneficiario::query();

        if ($municipio) {
            $query->where('municipio', $municipio);
        }

        $recorrencias = [];
        for ($i = 1; $i <= 6; $i++) {
            $q = clone $query;
            $q->where('rec' . $i, '>', 0);
            if ($ano) {
                $q->whereYear('data' . $i, $ano);
            }
            $recorrencias['Rec ' . $i] = $q->count();
        }

        $anos = [];
        for ($i = 1; $i <= 6; $i++) {
            $anosDaRec = Beneficiario::selectRaw('YEAR(data' . $i . ') as ano')
                ->whereNotNull('data' . $i)
                ->groupBy('ano')
                ->pluck('ano')
                ->toArray();
            $anos = array_merge($anos, $anosDaRec);
        }
        $anos = array_unique(array_filter($anos));
        sort($anos);

        return response()->json([
            'recorrencias' => $recorrencias,
            'anos'         => array_values($anos),
        ]);
    }

    // ================= IMPORTAÇÃO EXCEL =================
    public function importar(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new BeneficiariosImport;

        try {
            Excel::import($import, $request->file('file'));

            $falhas   = count($import->failures());
            $erros    = count($import->errors());
            $mensagem = 'Importação concluída com sucesso!';

            if ($falhas > 0 || $erros > 0) {
                $mensagem .= " (Atenção: {$falhas} linha(s) com falha de validação e {$erros} erro(s) ignorado(s))";
            }

            return back()->with('success', $mensagem);

        } catch (\Exception $e) {
            \Log::error('Erro na importação de beneficiários: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Erro ao importar: ' . $e->getMessage());
        }
    }

    // ================= FUNÇÕES AUXILIARES =================
    private function convertPago($valor)
    {
        $valor = strtolower(trim($valor));
        if ($valor == 'sim')                    return 1;
        if ($valor == 'não' || $valor == 'nao') return 0;
        if ($valor == 'nunca')                  return 2;
        return 0;
    }

    private function convertValor($valor)
    {
        if (empty($valor)) return 0;
        $valor = str_replace(['.', ','], '', $valor);
        return is_numeric($valor) ? (int)$valor : 0;
    }

    private function formatarData($valor)
    {
        try {
            if (empty($valor)) return null;
            return \Carbon\Carbon::parse($valor)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function utf8($valor)
    {
        if (empty($valor)) return null;
        return mb_convert_encoding($valor, 'UTF-8', 'ISO-8859-1');
    }
}