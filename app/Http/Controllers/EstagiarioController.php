<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estagiario;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstagiariosExport;

class EstagiarioController extends Controller
{
    // 📌 LISTAR
    public function index()
    {
        $estagiarios = Estagiario::all();
        return view('estagiarios.index', compact('estagiarios'));
    }

    // 📌 FORMULÁRIO DE CRIAÇÃO
    public function create()
    {
        return view('estagiarios.create');
    }

    // 📌 GUARDAR NO BANCO
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'sexo' => 'required',
            'bi' => 'required|unique:estagiarios,bi',
            'data_nascimento' => 'required|date',
            'estado' => 'required'
        ]);

        Estagiario::create($request->all());

        return redirect('/estagiarios')->with('success', 'Estagiário criado com sucesso!');
    }

    // 📌 FORMULÁRIO DE EDIÇÃO
    public function edit($id)
    {
        $estagiario = Estagiario::findOrFail($id);
        return view('estagiarios.edit', compact('estagiario'));
    }

    // 📌 ATUALIZAR
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required',
            'sexo' => 'required',
            'bi' => 'required|unique:estagiarios,bi,' . $id . ',id_estagiario',
            'data_nascimento' => 'required|date',
            'estado' => 'required'
        ]);

        $estagiario = Estagiario::findOrFail($id);
        $estagiario->update($request->all());

        return redirect('/estagiarios')->with('success', 'Estagiário atualizado com sucesso!');
    }

    // 📌 ELIMINAR
    public function destroy($id)
    {
        $estagiario = Estagiario::findOrFail($id);
        $estagiario->delete();

        return redirect('/estagiarios')->with('success', 'Estagiário eliminado com sucesso!');
    }

    public function exportPdf()
{
    $estagiarios = Estagiario::all();

    $pdf = Pdf::loadView('estagiarios.pdf', compact('estagiarios'))
        ->setPaper('a4', 'landscape');

    return $pdf->download('lista_estagiarios.pdf');
}

public function exportExcel()
{
    return Excel::download(new EstagiariosExport, 'estagiarios.xlsx');
}
}