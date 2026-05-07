@extends('layouts.app')

@section('titulo', 'Nova Função')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Nova Função</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Adicionar nova função ao sistema</p>
    </div>
    <a href="/funcoes" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="/funcoes" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nome da Função</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: Assistente de Dados">
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary px-4">
                            <i class="bi bi-plus-lg"></i> Guardar
                        </button>
                        <a href="/funcoes" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection