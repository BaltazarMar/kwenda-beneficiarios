<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EfetividadeController extends Controller
{
    public function index()
    {
        $funcionarios = Funcionario::with('funcao')->get();

        return view('efetividade.index', compact('funcionarios'));
    }
    

    public function pdf(Request $request)
    {
        $funcionarios = Funcionario::with('funcao')->get();

        $dias = $request->input('dias', []);
        $ferias = $request->input('ferias', []);
        $faltas = $request->input('faltas', []);
        $obs = $request->input('obs', []);

        $inicio = $request->inicio;
        $fim = $request->fim;

        $data = now();

        

        $pdf = Pdf::loadView('efetividade.pdf', compact(
            'funcionarios',
            'dias',
            'ferias',
            'faltas',
            'obs',
            'inicio',
            'fim',
            'data'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('folha-efetividade.pdf');
    }

    
}