@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Novo Funcionário</h2>

    <a href="/funcionarios" class="btn btn-secondary">⬅ Voltar</a>
</div>

<form action="/funcionarios" method="POST" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control">
    </div>

    <div class="mb-3">
        <label>Sexo</label>
        <select name="sexo" class="form-control">
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
        </select>
    </div>

    <div class="mb-3">
        <label>BI</label>
        <input type="text" name="bi" class="form-control">
    </div>

    <div class="mb-3">
        <label>Telefone</label>
        <input type="text" name="telefone" class="form-control">
    </div>

    <div class="mb-3">
        <label>Data de Entrada</label>
        <input type="date" name="data_entrada" class="form-control">
    </div>

    <div class="mb-3">
        <label>Função</label>
        <select name="id_funcao" class="form-control">
            <option value="">-- Selecionar Função --</option>
            @foreach($funcoes as $f)
                <option value="{{ $f->id_funcao }}">{{ $f->nome }}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary">Salvar</button>
</form>

@endsection