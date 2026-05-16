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
        <form method="GET" action="{{ url('/urbano-beneficiarios') }}" id="form-filtro">
            <div class="row g-2 align-items-end">

                {{-- CAMPO NOME COM AUTOCOMPLETE --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Nome</label>
                    <div class="autocomplete-wrap" style="position:relative;">
                        <input
                            type="text"
                            name="nome"
                            id="input-nome"
                            class="form-control form-control-sm"
                            placeholder="Pesquisar por nome..."
                            value="{{ request('nome') }}"
                            autocomplete="off"
                        >
                        <ul id="autocomplete-list" style="
                            display:none;
                            position:absolute;
                            top:100%;
                            left:0;
                            right:0;
                            z-index:1000;
                            background:#fff;
                            border:1px solid #e2e8f0;
                            border-top:none;
                            border-radius:0 0 8px 8px;
                            max-height:220px;
                            overflow-y:auto;
                            list-style:none;
                            margin:0;
                            padding:4px 0;
                            box-shadow:0 4px 12px rgba(0,0,0,0.08);
                        "></ul>
                    </div>
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

@push('scripts')
<script>
    const inputNome = document.getElementById('input-nome');
    const lista     = document.getElementById('autocomplete-list');
    let timeoutId   = null;

    inputNome.addEventListener('input', function () {
        const termo = this.value.trim();
        clearTimeout(timeoutId);
        fecharLista();

        if (termo.length < 1) return;

        // Espera 300ms antes de fazer o pedido (evita chamadas a cada tecla)
        timeoutId = setTimeout(() => {
            fetch(`/urbano-beneficiarios/sugestoes?nome=${encodeURIComponent(termo)}`)
                .then(res => res.json())
                .then(nomes => {
                    if (!nomes.length) return;

                    nomes.forEach(nome => {
                        const li = document.createElement('li');
                        li.textContent = nome;
                        li.style.cssText = `
                            padding: 8px 14px;
                            cursor: pointer;
                            font-size: 13px;
                            color: #0f172a;
                            transition: background 0.1s;
                        `;
                        li.addEventListener('mouseenter', () => li.style.background = '#f1f5f9');
                        li.addEventListener('mouseleave', () => li.style.background = '');
                        li.addEventListener('mousedown', () => {
                            inputNome.value = nome;
                            fecharLista();
                            // Submete o formulário automaticamente ao escolher
                            document.getElementById('form-filtro').submit();
                        });
                        lista.appendChild(li);
                    });

                    lista.style.display = 'block';
                });
        }, 300);
    });

    // Fecha a lista ao clicar fora
    document.addEventListener('click', function (e) {
        if (!inputNome.contains(e.target)) fecharLista();
    });

    // Navegação com teclado (setas + enter)
    inputNome.addEventListener('keydown', function (e) {
        const items = lista.querySelectorAll('li');
        let active  = lista.querySelector('li.ativo');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!active) {
                items[0]?.classList.add('ativo');
                items[0] && (items[0].style.background = '#f1f5f9');
            } else {
                const next = active.nextElementSibling;
                if (next) {
                    active.classList.remove('ativo');
                    active.style.background = '';
                    next.classList.add('ativo');
                    next.style.background = '#f1f5f9';
                }
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active) {
                const prev = active.previousElementSibling;
                active.classList.remove('ativo');
                active.style.background = '';
                if (prev) {
                    prev.classList.add('ativo');
                    prev.style.background = '#f1f5f9';
                }
            }
        } else if (e.key === 'Enter') {
            if (active) {
                e.preventDefault();
                inputNome.value = active.textContent;
                fecharLista();
                document.getElementById('form-filtro').submit();
            }
        } else if (e.key === 'Escape') {
            fecharLista();
        }
    });

    function fecharLista() {
        lista.innerHTML = '';
        lista.style.display = 'none';
    }
</script>
@endpush