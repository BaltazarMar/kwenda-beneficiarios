@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Funcionários</h2>

    <div>
        
        <a href="/funcionarios/create" class="btn btn-primary">+ Novo Funcionário</a>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Sexo</th>
            <th>BI</th>
            <th>Telefone</th>
            <th>Data Entrada</th>
            <th>Função</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        @foreach($funcionarios as $f)
        <tr>
            <td>{{ $f->nome }}</td>
            <td>{{ $f->sexo }}</td>
            <td>{{ $f->bi }}</td>
            <td>{{ $f->telefone }}</td>
            <td>{{ $f->data_entrada }}</td>
            <td>{{ $f->funcao->nome ?? '' }}</td>

            <td>
                <a href="/funcionarios/{{ $f->id_funcionario }}/edit" class="btn btn-warning btn-sm">Editar</a>

                <form action="/funcionarios/{{ $f->id_funcionario }}" method="POST" style="display:inline;">
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