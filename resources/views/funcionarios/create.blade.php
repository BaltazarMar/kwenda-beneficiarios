@extends('layouts.app')

@section('titulo', 'Novo Funcionário')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Novo Funcionário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Adicionar novo membro à equipa</p>
    </div>
    <a href="/funcionarios" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 640px;">
    <div class="card-body p-4">
        <form action="/funcionarios" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Nome</label>
                <input type="text" name="nome" class="form-control" placeholder="Nome completo">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Sexo</label>
                <select name="sexo" class="form-select">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">BI</label>
                <input type="text" name="bi" class="form-control" placeholder="Número do BI">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Telefone</label>
                <input type="text" name="telefone" class="form-control" placeholder="9XXXXXXXX">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Data de Entrada</label>
                <input type="date" name="data_entrada" class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Função</label>
                <select name="id_funcao" class="form-select">
                    <option value="">-- Selecionar Função --</option>
                    @foreach($funcoes as $f)
                        <option value="{{ $f->id_funcao }}">{{ $f->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary px-4">
                    <i class="bi bi-plus-lg"></i> Guardar
                </button>
                <a href="/funcionarios" class="btn btn-outline-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection