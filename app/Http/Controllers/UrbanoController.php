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

        $porMunicipio = BeneficiarioUrbano::selectRaw('municipio_residencia, COUNT(*) as total')
            ->whereNotNull('municipio_residencia')
            ->groupBy('municipio_residencia')
            ->orderByDesc('total')
            ->pluck('total', 'municipio_residencia');

        $bairros = BeneficiarioUrbano::whereNotNull('bairro')->distinct()->count('bairro');

        // NOVO: Estatísticas de pagamento
        $pagos = BeneficiarioUrbano::where('pago', 'sim')->count();
        $naoPagos = BeneficiarioUrbano::where('pago', 'nao')->count();
        $nuncaPagos = BeneficiarioUrbano::where('pago', 'nunca')->count();
        $valorTotal = BeneficiarioUrbano::sum('valor1') ?? 0;

        return view('urbano.dashboard', compact(
            'total', 'masculino', 'feminino',
            'porBairro', 'porCategoria', 'porMunicipio', 'bairros',
            'pagos', 'naoPagos', 'nuncaPagos', 'valorTotal'
        ));
    }

    // ================= DETALHES =================
    public function show(BeneficiarioUrbano $beneficiario)
    {
        return view('urbano.show', compact('beneficiario'));
    }

    // ================= EDITAR (FORMULÁRIO) =================
    public function edit(BeneficiarioUrbano $beneficiario)
    {
        return view('urbano.edit', compact('beneficiario'));
    }

    // ================= EDITAR (GUARDAR) =================
    public function update(Request $request, BeneficiarioUrbano $beneficiario)
    {
        $validated = $request->validate([
            'identificador'          => 'nullable|string|max:255',
            'nome'                   => 'required|string|max:255',
            'sexo'                   => 'nullable|in:M,F',
            'ip1'                    => 'nullable|string|max:255',
            'data_nascimento'        => 'nullable|date',
            'tipo_documento'         => 'nullable|string|max:255',
            'numero_documento'       => 'nullable|string|max:255',
            'municipio'              => 'nullable|string|max:255',
            'bairro'                 => 'nullable|string|max:255',
            'categoria'              => 'nullable|string|max:255',
            'observacao'             => 'nullable|string',
            'social_id'              => 'nullable|string|max:255',
            'numero_da_conta'        => 'nullable|string|max:255',
            'numero_administrativo'  => 'nullable|string|max:255',
            'card_id'                => 'nullable|string|max:255',
            'telefone'               => 'nullable|string|max:255',
            'agencia'                => 'nullable|string|max:255',
            'beneficiario'           => 'nullable|string|max:255',
            'contacto'               => 'nullable|string|max:255',
            'profissao'              => 'nullable|string|max:255',
            'provincia_residencia'   => 'nullable|string|max:255',
            'municipio_residencia'   => 'nullable|string|max:255',
            'comuna'                 => 'nullable|string|max:255',
            'data_inscricao'         => 'nullable|date',
            'pago'                   => 'nullable|in:sim,nao,nunca',
            'valor1'                 => 'nullable|numeric',
            'data1'                  => 'nullable|date',
            'rece_valor_agregado'    => 'nullable|numeric',
            'nome_valor_agregado'    => 'nullable|string|max:255',
            'coordenada_bancaria'    => 'nullable|string|max:255',
        ]);

        $beneficiario->update($validated);

        return redirect('/urbano-beneficiarios/' . $beneficiario->id)
            ->with('success', 'Beneficiário atualizado com sucesso!');
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

        // Filtro por pago (campo é string: 'sim' | 'nao' | 'nunca')
        if ($request->filled('pago')) {
            $query->where('pago', $request->pago);
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
            $query->where('municipio_residencia', $request->municipio);
        }
       if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
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
        $pagos = (clone $query)->where('pago', 'sim')->count();
        $naoPagos = (clone $query)->where('pago', 'nao')->count();
        $nuncaPagos = (clone $query)->where('pago', 'nunca')->count();
        $valorTotal = (clone $query)->sum('valor1') ?? 0;

        return response()->json([
            'total'        => $total,
            'masculino'    => $masculino,
            'feminino'     => $feminino,
            'bairros'      => $bairros,
            'porBairro'    => $porBairro,
            'porCategoria' => $porCategoria,
            'pagos'        => $pagos,
            'naoPagos'     => $naoPagos,
            'nuncaPagos'   => $nuncaPagos,
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

            $erros = $import->errors();
            $falhas = $import->failures();

            if ($erros->isNotEmpty() || $falhas->isNotEmpty()) {
                $mensagens = [];
                foreach ($erros as $erro) {
                    $mensagens[] = $erro->getMessage();
                }
                foreach ($falhas as $falha) {
                    $mensagens[] = "Linha {$falha->row()}: " . implode(', ', $falha->errors());
                }
                \Log::error('Erros na importação urbano: ' . implode(' | ', $mensagens));
                return back()->with('error', 'Importação com erros: ' . implode(' | ', array_slice($mensagens, 0, 5)));
            }

            return back()->with('success', 'Importacao concluida com sucesso! Total: ' . BeneficiarioUrbano::count());
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao importar: ' . $e->getMessage());
        }
    }
}