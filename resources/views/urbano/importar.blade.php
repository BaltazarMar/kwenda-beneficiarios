@extends('layouts.app')

@section('titulo', 'Importar Kwenda Urbano')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Importar Kwenda Urbano</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Importar ficheiro Excel dos beneficiários urbanos</p>
    </div>
    <a href="{{ url('/urbano-dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="/urbano-importar" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Ficheiro Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Formatos aceites: .xlsx, .xls</small>
                    </div>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-upload"></i> Importar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection