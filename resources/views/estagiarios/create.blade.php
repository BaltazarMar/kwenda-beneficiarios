@extends('layouts.app')

@section('content')

<h2>Adicionar Estagiário</h2>

<form action="/estagiarios" method="POST" class="card p-4">
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
        <label>Data de Nascimento</label>
        <input type="date" name="data_nascimento" class="form-control">
    </div>

    <div class="mb-3">
        <label>Estado</label>
        <select name="estado" class="form-control">
            <option value="activo">Activo</option>
            <option value="terminado">Terminado</option>
        </select>
    </div>

    <button class="btn btn-success">Guardar</button>
</form>

@endsection