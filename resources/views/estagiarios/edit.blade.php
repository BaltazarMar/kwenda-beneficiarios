@extends('layouts.app')

@section('titulo', 'Editar Estagiário')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar Estagiário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Actualizar dados do estagiário</p>
    </div>
    <a href="/estagiarios" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="/estagiarios/{{ $estagiario->id_estagiario }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nome</label>
                        <input type="text" name="nome" class="form-control" value="{{ $estagiario->nome }}">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px;">Sexo</label>
                            <select name="sexo" class="form-select">
                                <option value="M" {{ $estagiario->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ $estagiario->sexo == 'F' ? 'selected' : '' }}>Feminino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px;">BI</label>
                            <input type="text" name="bi" class="form-control" value="{{ $estagiario->bi }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px;">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="{{ $estagiario->telefone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px;">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" class="form-control" value="{{ $estagiario->data_nascimento }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Curso / Licenciatura</label>
                        <input type="text" name="curso" class="form-control" value="{{ $estagiario->curso }}">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px;">Data de Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ $estagiario->data_inicio }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px;">Data de Término</label>
                            <input type="date" name="data_termino" class="form-control" value="{{ $estagiario->data_termino }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="activo" {{ $estagiario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="terminado" {{ $estagiario->estado == 'terminado' ? 'selected' : '' }}>Terminado</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary px-4">
                            <i class="bi bi-check-lg"></i> Actualizar
                        </button>
                        <a href="/estagiarios" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection