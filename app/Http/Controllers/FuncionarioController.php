<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Funcao;

class FuncionarioController extends Controller
{
    // 📌 LISTAR
   public function index()
{
    $funcionarios = Funcionario::with('funcao')->get();

    return view('funcionarios.index', compact('funcionarios'));
}

    // 📌 FORM CREATE
    public function create()
    {
        $funcoes = Funcao::all();

        return view('funcionarios.create', compact('funcoes'));
    }

    // 📌 GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'sexo' => 'required',
            'bi' => 'required|unique:funcionarios,bi',
            'telefone' => 'required',
            'data_entrada' => 'required|date',
            'id_funcao' => 'required'
        ]);

        Funcionario::create($request->all());

        return redirect('/funcionarios')->with('success', 'Funcionário criado com sucesso!');
    }

    // 📌 EDIT
    public function edit($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcoes = Funcao::all();

        return view('funcionarios.edit', compact('funcionario', 'funcoes'));
    }

    // 📌 UPDATE
    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'sexo' => 'required',
            'bi' => 'required|unique:funcionarios,bi,' . $id . ',id_funcionario',
            'telefone' => 'required',
            'data_entrada' => 'required|date',
            'id_funcao' => 'required'
        ]);

        $funcionario->update($request->all());

        return redirect('/funcionarios')->with('success', 'Funcionário atualizado com sucesso!');
    }

    // 📌 DELETE
    public function destroy($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->delete();

        return redirect('/funcionarios')->with('success', 'Funcionário eliminado com sucesso!');
    }
    
}