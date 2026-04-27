@extends('layouts.app')

@section('content')

<h2>Editar Estagiário</h2>

<form action="/estagiarios/{{ $estagiario->id_estagiario }}" method="POST" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" value="{{ $estagiario->nome }}">
    </div>

    <div class="mb-3">
        <label>Sexo</label>
        <select name="sexo" class="form-control">
            <option value="M" {{ $estagiario->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
            <option value="F" {{ $estagiario->sexo == 'F' ? 'selected' : '' }}>Feminino</option>
        </select>
    </div>

    <div class="mb-3">
        <label>BI</label>
        <input type="text" name="bi" class="form-control" value="{{ $estagiario->bi }}">
    </div>

    <div class="mb-3">
        <label>Data de Nascimento</label>
        <input type="date" name="data_nascimento" class="form-control" value="{{ $estagiario->data_nascimento }}">
    </div>

    <div class="mb-3">
        <label>Estado</label>
        <select name="estado" class="form-control">
            <option value="activo" {{ $estagiario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="terminado" {{ $estagiario->estado == 'terminado' ? 'selected' : '' }}>Terminado</option>
        </select>
    </div>

    <button class="btn btn-success">Atualizar</button>
</form>

@endsection