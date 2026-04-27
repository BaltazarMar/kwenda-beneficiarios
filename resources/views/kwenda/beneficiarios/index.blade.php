@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- FILTROS --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ url('/beneficiarios') }}">
                <div class="row g-2">

     <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Beneficiários</h4>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-primary fs-6">Total: {{ $beneficiarios->total() }}</span>

        {{-- Registos por página --}}
        <form method="GET" action="{{ url('/beneficiarios') }}" id="form-perpage">
            @foreach(request()->except('per_page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="per_page" class="form-select form-select-sm w-auto" onchange="document.getElementById('form-perpage').submit()">
                <option value="25"  {{ request('per_page', 25) == 25  ? 'selected' : '' }}>25</option>
                <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>

        <a href="{{ url('/beneficiarios/exportar') }}?{{ http_build_query(request()->all()) }}"
           class="btn btn-success btn-sm">
            📥 Exportar Excel
        </a>
    </div>
</div>

                    <div class="col-12 col-md-3">
                        <input
                            type="text"
                            name="nome"
                            class="form-control"
                            placeholder="Pesquisar por nome..."
                            value="{{ request('nome') }}"
                        >
                    </div>

                    <div class="col-6 col-md-2">
                        <input
                            type="text"
                            name="social_id"
                            class="form-control"
                            placeholder="Social ID"
                            value="{{ request('social_id') }}"
                        >
                    </div>

                    <div class="col-6 col-md-2">
                        <select name="municipio" id="select-municipio" class="form-select">
                            <option value="">Todos os municípios</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio }}" {{ request('municipio') == $municipio ? 'selected' : '' }}>
                                    {{ $municipio }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <select name="bairro" id="select-bairro" class="form-select">
                            <option value="">Todos os bairros</option>
                            @foreach($bairros as $bairro)
                                <option value="{{ $bairro }}" {{ request('bairro') == $bairro ? 'selected' : '' }}>
                                    {{ $bairro }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-1">
                        <select name="pago" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('pago') === '1' ? 'selected' : '' }}>Pago</option>
                            <option value="0" {{ request('pago') === '0' ? 'selected' : '' }}>Não Pago</option>
                            <option value="2" {{ request('pago') === '2' ? 'selected' : '' }}>Nunca Pago</option>
                        </select>
                    </div>


                    <div class="col-12 col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        <a href="{{ url('/beneficiarios') }}" class="btn btn-outline-secondary w-100">Limpar</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Social ID</th>
                            <th>Nome</th>
                            <th>Sexo</th>
                            <th>Município</th>
                            <th>Contacto</th>
                            <th>Pago</th>
                            <th>Total Recebido</th>
                            <th>Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beneficiarios as $b)
                        <tr>
                            <td>{{ $b->social_id }}</td>
                            <td>{{ $b->nome }}</td>
                            <td>
                                @if($b->sexo == 'M')
                                    <span class="badge bg-info">M</span>
                                @elseif($b->sexo == 'F')
                                    <span class="badge" style="background-color:#e91e8c;">F</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $b->municipio }}</td>
                            <td>{{ $b->contacto ?? '—' }}</td>
                            <td>
                                @if($b->pago == 1)
                                    <span class="badge bg-success">Pago</span>
                                @elseif($b->pago == 0)
                                    <span class="badge bg-danger">Não Pago</span>
                                @else
                                    <span class="badge bg-warning text-dark">Nunca</span>
                                @endif
                            </td>
                            <td>
                                {{ number_format($b->rec1 + $b->rec2 + $b->rec3 + $b->rec4 + $b->rec5 + $b->rec6, 0, ',', '.') }} Kz
                            </td>
                            <td>
                                <a href="{{ url('/beneficiarios/' . $b->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
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

</div>
@endsection

@push('scripts')
<script>
    const selectMunicipio = document.getElementById('select-municipio');
    const selectBairro    = document.getElementById('select-bairro');
    const bairroActivo    = "{{ request('bairro') }}";

    selectMunicipio.addEventListener('change', function() {
        const municipio = this.value;

        selectBairro.innerHTML = '<option value="">A carregar...</option>';
        selectBairro.disabled = true;

        fetch(`/bairros-por-municipio?municipio=${encodeURIComponent(municipio)}`)
            .then(res => res.json())
            .then(bairros => {
                selectBairro.innerHTML = '<option value="">Todos os bairros</option>';
                bairros.forEach(bairro => {
                    const opt       = document.createElement('option');
                    opt.value       = bairro;
                    opt.textContent = bairro;
                    if (bairro === bairroActivo) opt.selected = true;
                    selectBairro.appendChild(opt);
                });
                selectBairro.disabled = false;
            });
    });
</script>
@endpush