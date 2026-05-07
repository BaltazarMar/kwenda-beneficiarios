@extends('layouts.app')

@section('titulo', 'Funções')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Funções</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Gestão de funções</p>
    </div>
    <a href="/funcoes/create" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-lg"></i> Nova Função
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead style="background:#f8fafc;">
                <tr>
                    <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">ID</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Nome da Função</th>
                    <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Acções</th>
                </tr>
            </thead>
            <tbody>
                @foreach($funcoes as $f)
                <tr>
                    <td class="ps-4 py-3 text-muted" style="font-size:13px;">{{ $f->id_funcao }}</td>
                    <td class="py-3 fw-semibold">{{ $f->nome }}</td>
                    <td class="py-3">
                        <a href="/funcoes/{{ $f->id_funcao }}/edit" class="btn btn-sm" style="background:#fffbeb; color:#d97706; border:none; font-weight:600; font-size:12px;">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                        <form action="/funcoes/{{ $f->id_funcao }}" method="POST" style="display:inline;">
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