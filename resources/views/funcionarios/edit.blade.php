@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Editar Funcionário</h2>

    <a href="/funcionarios" class="btn btn-secondary">⬅ Voltar</a>
</div>

<form action="/funcionarios/{{ $funcionario->id_funcionario }}" method="POST" class="card p-4">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" value="{{ $funcionario->nome }}">
    </div>

    <div class="mb-3">
        <label>Sexo</label>
        <select name="sexo" class="form-control">
            <option value="M" {{ $funcionario->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
            <option value="F" {{ $funcionario->sexo == 'F' ? 'selected' : '' }}>Feminino</option>
        </select>
    </div>

    <div class="mb-3">
        <label>BI</label>
        <input type="text" name="bi" class="form-control" value="{{ $funcionario->bi }}">
    </div>

    <div class="mb-3">
        <label>Telefone</label>
        <input type="text" name="telefone" class="form-control" value="{{ $funcionario->telefone }}">
    </div>

    <div class="mb-3">
        <label>Data de Entrada</label>
        <input type="date" name="data_entrada" class="form-control" value="{{ $funcionario->data_entrada }}">
    </div>

    <div class="mb-3">
        <label>Função</label>
        <select name="id_funcao" class="form-control">
            @foreach($funcoes as $f)
                <option value="{{ $f->id_funcao }}"
                    {{ $funcionario->id_funcao == $f->id_funcao ? 'selected' : '' }}>
                    {{ $f->nome }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary">Atualizar</button>
</form>

@endsection