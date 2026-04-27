@extends('layouts.app')

@section('content')
<a href="/estagiarios/pdf" class="btn btn-danger mb-3">
    📄 Exportar PDF
</a>
<a href="{{ route('estagiarios.excel') }}" class="btn btn-success">
    📊 Exportar Excel
</a>
<div class="d-flex justify-content-between mb-3">
    <h2>Estagiários</h2>

    <a href="/estagiarios/create" class="btn btn-primary">
        + Adicionar
    </a>

    
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sexo</th>
            <th>BI</th>
            <th>Data Nascimento</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        @foreach($estagiarios as $estagiario)
        <tr>
            <td>{{ $estagiario->id_estagiario }}</td>
            <td>{{ $estagiario->nome }}</td>
            <td>{{ $estagiario->sexo }}</td>
            <td>{{ $estagiario->bi }}</td>
            <td>{{ $estagiario->data_nascimento }}</td>
            <td>
                <span class="badge bg-{{ $estagiario->estado == 'activo' ? 'success' : 'secondary' }}">
                    {{ $estagiario->estado }}
                </span>
            </td>
            <td>
                <a href="/estagiarios/{{ $estagiario->id_estagiario }}/edit" class="btn btn-warning btn-sm">
                    Editar
                </a>

                <form action="/estagiarios/{{ $estagiario->id_estagiario }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm" onclick="return confirm('Eliminar?')">
                        Eliminar
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection