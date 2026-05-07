@extends('layouts.app')

@section('titulo', 'Funcionários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Funcionários</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Gestão da equipa</p>
    </div>
    <a href="/funcionarios/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Novo Funcionário
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead style="background:#f8fafc;">
                <tr>
                    <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Sexo</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">BI</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Telefone</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Data Entrada</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Função</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Acções</th>
                </tr>
            </thead>
            <tbody>
                @foreach($funcionarios as $f)
                <tr>
                    <td class="ps-4 py-3 fw-semibold">{{ $f->nome }}</td>
                    <td class="py-3">
                        @if($f->sexo == 'M')
                            <span class="badge" style="background:#eff6ff; color:#3b82f6;">M</span>
                        @else
                            <span class="badge" style="background:#fdf2f8; color:#ec4899;">F</span>
                        @endif
                    </td>
                    <td class="py-3 text-muted">{{ $f->bi }}</td>
                    <td class="py-3 text-muted">{{ $f->telefone }}</td>
                    <td class="py-3 text-muted">{{ $f->data_entrada }}</td>
                    <td class="py-3">
                        <span class="badge" style="background:#f0fdf4; color:#16a34a; font-weight:600;">{{ $f->funcao->nome ?? '—' }}</span>
                    </td>
                    <td class="py-3">
                        <a href="/funcionarios/{{ $f->id_funcionario }}/edit" class="btn btn-sm" style="background:#fffbeb; color:#d97706; border:none; font-weight:600; font-size:12px;">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                        <form action="/funcionarios/{{ $f->id_funcionario }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm" style="background:#fff1f2; color:#dc2626; border:none; font-weight:600; font-size:12px;">
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
@endsection