@extends('layouts.app')

@section('titulo', 'Folha de Efetividade')

@section('content')

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Folha de Efetividade</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Controlo de presenças, faltas e férias</p>
    </div>
</div>

<form action="/efetividade/pdf" method="POST">
    @csrf

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:13px; color:#64748b;">Data Início</label>
                    <input type="date" name="inicio" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" style="font-size:13px; color:#64748b;">Data Fim</label>
                    <input type="date" name="fim" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Baixar PDF
                    </button>
                    <a href="/efetividades/csv" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; width:50px;">Nº</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Função</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; width:140px;">Dias de Trabalho</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; width:120px;">Férias</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; width:120px;">Faltas</th>
                            <th class="py-3 pe-4" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funcionarios as $key => $f)
                        <tr>
                            <td class="ps-4 py-3 text-muted" style="font-size:13px;">{{ $key + 1 }}</td>
                            <td class="py-3 fw-semibold">{{ $f->nome }}</td>
                            <td class="py-3">
                                <span class="badge" style="background:#f0fdf4; color:#16a34a; font-weight:600;">
                                    {{ optional($f->funcao)->nome ?? '—' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <input type="number" name="dias[{{ $f->id_funcionario }}]" value="22"
                                    class="form-control form-control-sm text-center"
                                    style="border-radius:8px; border:1px solid #e2e8f0;">
                            </td>
                            <td class="py-3">
                                <input type="number" name="ferias[{{ $f->id_funcionario }}]" value="0"
                                    class="form-control form-control-sm text-center"
                                    style="border-radius:8px; border:1px solid #e2e8f0;">
                            </td>
                            <td class="py-3">
                                <input type="number" name="faltas[{{ $f->id_funcionario }}]" value="0"
                                    class="form-control form-control-sm text-center"
                                    style="border-radius:8px; border:1px solid #e2e8f0;">
                            </td>
                            <td class="py-3 pe-4">
                                <input type="text" name="obs[{{ $f->id_funcionario }}]"
                                    class="form-control form-control-sm"
                                    placeholder="Observação..."
                                    style="border-radius:8px; border:1px solid #e2e8f0;">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</form>

@endsection