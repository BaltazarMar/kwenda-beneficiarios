@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Funções</h2>

    <div>
        <a href="/dashboard" class="btn btn-secondary">⬅ Voltar</a>
        <a href="/funcoes/create" class="btn btn-primary">+ Nova Função</a>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome da Função</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        @foreach($funcoes as $f)
        <tr>
            <td>{{ $f->id_funcao }}</td>
            <td>{{ $f->nome }}</td>

            <td>
                <a href="/funcoes/{{ $f->id_funcao }}/edit" class="btn btn-warning btn-sm">Editar</a>

                <form action="/funcoes/{{ $f->id_funcao }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection