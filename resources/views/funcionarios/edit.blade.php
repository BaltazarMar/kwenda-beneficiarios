@extends('layouts.app')

@section('titulo', 'Editar Funcionário')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar Funcionário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Actualizar dados do funcionário</p>
    </div>
    <a href="/funcionarios" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 640px;">
    <div class="card-body p-4">
        <form action="/funcionarios/{{ $funcionario->id_funcionario }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Nome</label>
                <input type="text" name="nome" class="form-control" value="{{ $funcionario->nome }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Sexo</label>
                <select name="sexo" class="form-select">
                    <option value="M" {{ $funcionario->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ $funcionario->sexo == 'F' ? 'selected' : '' }}>Feminino</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">BI</label>
                <input type="text" name="bi" class="form-control" value="{{ $funcionario->bi }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="{{ $funcionario->telefone }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Data de Entrada</label>
                <input type="date" name="data_entrada" class="form-control" value="{{ $funcionario->data_entrada }}">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Função</label>
                <select name="id_funcao" class="form-select">
                    @foreach($funcoes as $f)
                        <option value="{{ $f->id_funcao }}" {{ $funcionario->id_funcao == $f->id_funcao ? 'selected' : '' }}>
                            {{ $f->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary px-4">
                    <i class="bi bi-check-lg"></i> Actualizar
                </button>
                <a href="/funcionarios" class="btn btn-outline-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection