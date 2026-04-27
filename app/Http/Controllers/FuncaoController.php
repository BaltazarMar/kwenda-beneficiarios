<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcao;

class FuncaoController extends Controller
{
    // 📌 INDEX
    public function index()
    {
        $funcoes = Funcao::all();

        return view('funcoes.index', compact('funcoes'));
    }

    // 📌 CREATE
    public function create()
    {
        return view('funcoes.create');
    }

    // 📌 STORE
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required'
        ]);

        Funcao::create($request->all());

        return redirect('/funcoes')->with('success', 'Função criada com sucesso!');
    }

    // 📌 EDIT
    public function edit($id)
    {
        $funcao = Funcao::findOrFail($id);

        return view('funcoes.edit', compact('funcao'));
    }

    // 📌 UPDATE
    public function update(Request $request, $id)
    {
        $funcao = Funcao::findOrFail($id);

        $request->validate([
            'nome' => 'required'
        ]);

        $funcao->update($request->all());

        return redirect('/funcoes')->with('success', 'Função atualizada com sucesso!');
    }

    // 📌 DELETE
    public function destroy($id)
    {
        $funcao = Funcao::findOrFail($id);
        $funcao->delete();

        return redirect('/funcoes')->with('success', 'Função eliminada com sucesso!');
    }
}