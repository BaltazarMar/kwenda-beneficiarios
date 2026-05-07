@extends('layouts.app')

@section('titulo', 'Adicionar Estagiário')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Adicionar Estagiário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Registar novo estagiário</p>
    </div>
    <a href="/estagiarios" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 640px;">
    <div class="card-body p-4">
        <form action="/estagiarios" method="POST">
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
                <label class="form-label fw-semibold" style="font-size:13px;">Data de Nascimento</label>
                <input type="date" name="data_nascimento" class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Estado</label>
                <select name="estado" class="form-select">
                    <option value="activo">Activo</option>
                    <option value="terminado">Terminado</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary px-4">
                    <i class="bi bi-plus-lg"></i> Guardar
                </button>
                <a href="/estagiarios" class="btn btn-outline-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection