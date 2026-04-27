@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Editar Função</h2>

    <a href="/funcoes" class="btn btn-secondary">⬅ Voltar</a>
</div>

<form action="/funcoes/{{ $funcao->id_funcao }}" method="POST" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nome da Função</label>
        <input type="text" name="nome" class="form-control" value="{{ $funcao->nome }}">
    </div>

    <button class="btn btn-primary">Atualizar</button>
</form>

@endsection