@extends('layouts.app')

@section('titulo', 'Estagiários')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Estagiários</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Gestão de estagiários</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/estagiarios/pdf" class="btn btn-sm btn-danger">
            <i class="bi bi-file-earmark-pdf-fill"></i> Exportar PDF
        </a>
        <a href="/estagiarios/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Adicionar
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Sexo</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">BI</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Telefone</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Curso</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Data Nasc.</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Data Início</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Data Término</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Estado</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Acções</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estagiarios as $estagiario)
                    <tr>
                        <td class="ps-4 py-3 fw-semibold">{{ $estagiario->nome }}</td>
                        <td class="py-3">
                            @if($estagiario->sexo == 'M')
                                <span class="badge" style="background:#eff6ff; color:#3b82f6;">M</span>
                            @else
                                <span class="badge" style="background:#fdf2f8; color:#ec4899;">F</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted">{{ $estagiario->bi ?? '—' }}</td>
                        <td class="py-3 text-muted">{{ $estagiario->telefone ?? '—' }}</td>
                        <td class="py-3 text-muted">{{ $estagiario->curso ?? '—' }}</td>
                        <td class="py-3 text-muted">{{ $estagiario->data_nascimento ? \Carbon\Carbon::parse($estagiario->data_nascimento)->format('d/m/Y') : '—' }}</td>
                        <td class="py-3 text-muted">{{ $estagiario->data_inicio ? \Carbon\Carbon::parse($estagiario->data_inicio)->format('d/m/Y') : '—' }}</td>
                        <td class="py-3 text-muted">{{ $estagiario->data_termino ? \Carbon\Carbon::parse($estagiario->data_termino)->format('d/m/Y') : '—' }}</td>
                        <td class="py-3">
                            @if($estagiario->estado == 'activo')
                                <span class="badge" style="background:#f0fdf4; color:#16a34a; font-weight:600;">Activo</span>
                            @else
                                <span class="badge" style="background:#f8fafc; color:#64748b; font-weight:600;">Terminado</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <a href="/estagiarios/{{ $estagiario->id_estagiario }}/edit" class="btn btn-sm" style="background:#fffbeb; color:#d97706; border:none; font-weight:600; font-size:12px;">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            <form action="/estagiarios/{{ $estagiario->id_estagiario }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm" style="background:#fff1f2; color:#dc2626; border:none; font-weight:600; font-size:12px;" onclick="return confirm('Eliminar?')">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection