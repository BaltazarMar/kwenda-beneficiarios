@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Nova Função</h2>

    <a href="/funcoes" class="btn btn-secondary">⬅ Voltar</a>
</div>

<form action="/funcoes" method="POST" class="card p-4">
    @csrf

    <div class="mb-3">
        <label>Nome da Função</label>
        <input type="text" name="nome" class="form-control">
    </div>

    <button class="btn btn-primary">Salvar</button>
</form>

@endsection