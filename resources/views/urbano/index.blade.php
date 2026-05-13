@extends('layouts.app')

@section('titulo', 'Beneficiários Urbanos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Beneficiários Urbanos</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Total: <strong>{{ $beneficiarios->total() }}</strong> registos</p>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" action="{{ url('/urbano-beneficiarios') }}" id="form-perpage">
            @foreach(request()->except('per_page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="per_page" class="form-select form-select-sm w-auto" onchange="document.getElementById('form-perpage').submit()">
                <option value="25"  {{ request('per_page', 25) == 25  ? 'selected' : '' }}>25</option>
                <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
    </div>
</div>

{{-- FILTROS --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/urbano-beneficiarios') }}">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Nome</label>
                    <input type="text" name="nome" class="form-control form-control-sm" placeholder="Pesquisar por nome..." value="{{ request('nome') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Identificador</label>
                    <input type="text" name="identificador" class="form-control form-control-sm" placeholder="ID" value="{{ request('identificador') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Bairro</label>
                    <select name="bairro" class="form-select form-select-sm">
                        <option value="">Todos os bairros</option>
                        @foreach($bairros as $bairro)
                            <option value="{{ $bairro }}" {{ request('bairro') == $bairro ? 'selected' : '' }}>{{ $bairro }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Categoria</label>
                    <select name="categoria" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Sexo</label>
                    <select name="sexo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="M" {{ request('sexo') == 'M' ? 'selected' : '' }}>M</option>
                        <option value="F" {{ request('sexo') == 'F' ? 'selected' : '' }}>F</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ url('/urbano-beneficiarios') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABELA --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Identificador</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Sexo</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Data Nasc.</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Bairro</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Categoria</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Documento</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiarios as $b)
                    <tr>
                        <td class="ps-4 py-3 text-muted" style="font-size:12px;">{{ $b->identificador }}</td>
                        <td class="py-3 fw-semibold" style="white-space:nowrap;">{{ $b->nome }}</td>
                        <td class="py-3">
                            @if($b->sexo == 'M')
                                <span class="badge" style="background:#eff6ff; color:#3b82f6;">M</span>
                            @elseif($b->sexo == 'F')
                                <span class="badge" style="background:#fdf2f8; color:#ec4899;">F</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted">{{ $b->data_nascimento ? $b->data_nascimento->format('d/m/Y') : '—' }}</td>
                        <td class="py-3 text-muted">{{ $b->bairro ?? '—' }}</td>
                        <td class="py-3">
                            @if($b->categoria)
                                <span class="badge" style="background:#f0fdf4; color:#16a34a; font-weight:600;">{{ $b->categoria }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted" style="font-size:12px;">{{ $b->numero_documento ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                            Nenhum beneficiário encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PAGINAÇÃO --}}
<div class="d-flex justify-content-center mt-4">
    {{ $beneficiarios->links() }}
</div>

@endsection