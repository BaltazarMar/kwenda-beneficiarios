<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioUrbano;
use Illuminate\Http\Request;
use App\Imports\BeneficiariosUrbanoImport;
use Maatwebsite\Excel\Facades\Excel;

class UrbanoController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        $total     = BeneficiarioUrbano::count();
        $masculino = BeneficiarioUrbano::where('sexo', 'M')->count();
        $feminino  = BeneficiarioUrbano::where('sexo', 'F')->count();

        $porBairro = BeneficiarioUrbano::selectRaw('bairro, COUNT(*) as total')
            ->whereNotNull('bairro')
            ->groupBy('bairro')
            ->orderByDesc('total')
            ->pluck('total', 'bairro');

        $porCategoria = BeneficiarioUrbano::selectRaw('categoria, COUNT(*) as total')
            ->whereNotNull('categoria')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->pluck('total', 'categoria');

        $porMunicipio = BeneficiarioUrbano::selectRaw('municipio, COUNT(*) as total')
            ->whereNotNull('municipio')
            ->groupBy('municipio')
            ->orderByDesc('total')
            ->pluck('total', 'municipio');

        $bairros = BeneficiarioUrbano::whereNotNull('bairro')->distinct()->count('bairro');

        // NOVO: Estatísticas de pagamento
        $pagos = BeneficiarioUrbano::where('pago', true)->count();
        $valorTotal = BeneficiarioUrbano::sum('valor1') ?? 0;

        return view('urbano.dashboard', compact(
            'total', 'masculino', 'feminino',
            'porBairro', 'porCategoria', 'porMunicipio', 'bairros',
            'pagos', 'valorTotal'
        ));
    }

    // ================= LISTAGEM =================
    public function index(Request $request)
    {
        $query = BeneficiarioUrbano::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('identificador')) {
            $query->where('identificador', $request->identificador);
        }

        if ($request->filled('bairro')) {
            $query->where('bairro', $request->bairro);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        // NOVO: Filtro por telefone
        if ($request->filled('telefone')) {
            $query->where('telefone', 'like', '%' . $request->telefone . '%');
        }

        // NOVO: Filtro por pago
        if ($request->filled('pago')) {
            $pago = $request->pago === 'sim' ? true : false;
            $query->where('pago', $pago);
        }

        $perPage = in_array($request->per_page, [25, 50, 100]) ? $request->per_page : 25;
        $beneficiarios = $query->orderBy('nome')->paginate($perPage)->withQueryString();

        $bairros    = BeneficiarioUrbano::whereNotNull('bairro')->distinct()->orderBy('bairro')->pluck('bairro');
        $categorias = BeneficiarioUrbano::whereNotNull('categoria')->distinct()->orderBy('categoria')->pluck('categoria');

        return view('urbano.index', compact('beneficiarios', 'bairros', 'categorias'));
    }

    // ================= AUTOCOMPLETE — respeita filtros activos =================
    public function sugestoes(Request $request)
    {
        $termo = $request->get('nome', '');

        if (strlen($termo) < 1) {
            return response()->json([]);
        }

        $query = BeneficiarioUrbano::where('nome', 'like', $termo . '%');

        if ($request->filled('bairro'))    $query->where('bairro', $request->bairro);
        if ($request->filled('categoria')) $query->where('categoria', $request->categoria);
        if ($request->filled('sexo'))      $query->where('sexo', $request->sexo);

        $nomes = $query->orderBy('nome')->limit(10)->pluck('nome');

        return response()->json($nomes);
    }

    // ================= FILTROS DASHBOARD =================
    public function filtros(Request $request)
    {
        $query = BeneficiarioUrbano::query();

        if ($request->filled('municipio')) {
            $query->where('municipio', $request->municipio);
        }

        $total     = $query->count();
        $masculino = (clone $query)->where('sexo', 'M')->count();
        $feminino  = (clone $query)->where('sexo', 'F')->count();

        $porBairro = (clone $query)
            ->selectRaw('bairro, COUNT(*) as total')
            ->whereNotNull('bairro')
            ->groupBy('bairro')
            ->orderByDesc('total')
            ->pluck('total', 'bairro');

        $porCategoria = (clone $query)
            ->selectRaw('categoria, COUNT(*) as total')
            ->whereNotNull('categoria')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->pluck('total', 'categoria');

        $bairros = (clone $query)->whereNotNull('bairro')->distinct()->count('bairro');

        // NOVO: Estatísticas de pagamento
        $pagos = (clone $query)->where('pago', true)->count();
        $valorTotal = (clone $query)->sum('valor1') ?? 0;

        return response()->json([
            'total'        => $total,
            'masculino'    => $masculino,
            'feminino'     => $feminino,
            'bairros'      => $bairros,
            'porBairro'    => $porBairro,
            'porCategoria' => $porCategoria,
            'pagos'        => $pagos,
            'valorTotal'   => number_format($valorTotal, 2, ',', '.'),
        ]);
    }

    // ================= IMPORTACAO =================
    public function importar(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        $import = new BeneficiariosUrbanoImport;

        try {
            Excel::import($import, $request->file('file'));
            return back()->with('success', 'Importacao concluida com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao importar: ' . $e->getMessage());
        }
    }
}