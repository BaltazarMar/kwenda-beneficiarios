@extends('layouts.app')

@section('titulo', 'Beneficiários')

@section('content')

<style>
    .multi-filtro { position: relative; }
    .multi-filtro .multi-botao {
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }
    .multi-filtro .multi-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 100%;
        width: max-content;
        max-width: 280px;
        z-index: 1050;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 10px;
        max-height: 260px;
        overflow-y: auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .multi-filtro.aberto .multi-menu { display: block; }
    .multi-filtro .multi-limpar {
        display: block;
        font-size: 11px;
        color: #3b82f6;
        text-decoration: none;
        margin-bottom: 6px;
    }
</style>

@php
    $municipiosSel = array_map('strval', (array) request('municipio', []));
    $bairrosSel    = array_map('strval', (array) request('bairro', []));
    $pagoSel       = array_map('strval', (array) request('pago', []));
@endphp

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Lista de Beneficiários</h4>
        <p class="text-muted mb-0" style="font-size:13px;">
            Total filtrado: <strong>{{ $beneficiarios->total() }}</strong> registos
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" action="{{ url('/beneficiarios') }}" id="form-perpage" class="d-flex align-items-center gap-2">
            @foreach(request()->except('per_page') as $key => $value)
                @foreach((array) $value as $v)
                    <input type="hidden" name="{{ $key }}{{ is_array($value) ? '[]' : '' }}" value="{{ $v }}">
                @endforeach
            @endforeach
            <select name="per_page" class="form-select form-select-sm w-auto" onchange="document.getElementById('form-perpage').submit()">
                <option value="25"  {{ request('per_page', 25) == 25  ? 'selected' : '' }}>25</option>
                <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
        <a href="{{ url('/beneficiarios/exportar') }}?{{ http_build_query(request()->all()) }}" class="btn btn-sm btn-success">
            <i class="bi bi-download"></i> Exportar Excel
        </a>
    </div>
</div>

{{-- FILTROS --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/beneficiarios') }}" id="form-filtro">
            <div class="row g-2 align-items-end">

                {{-- CAMPO NOME COM AUTOCOMPLETE --}}
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Nome</label>
                    <div style="position:relative;">
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
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Social ID</label>
                    <input type="text" name="social_id" class="form-control form-control-sm" placeholder="Social ID" value="{{ request('social_id') }}">
                </div>

                {{-- MUNICÍPIO (MÚLTIPLO) --}}
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Município</label>
                    <div class="multi-filtro" id="multi-municipio" data-rotulo="Todos os municípios">
                        <button type="button" class="form-select form-select-sm multi-botao">
                            <span class="multi-rotulo">Todos os municípios</span>
                        </button>
                        <div class="multi-menu">
                            <a href="#" class="multi-limpar">Limpar seleção</a>
                            <div class="multi-opcoes">
                                @foreach($municipios as $municipio)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="municipio[]"
                                               value="{{ $municipio }}" id="mun-{{ $loop->index }}"
                                               {{ in_array((string) $municipio, $municipiosSel, true) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size:13px;" for="mun-{{ $loop->index }}">{{ $municipio }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BAIRRO (MÚLTIPLO) --}}
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Bairro</label>
                    <div class="multi-filtro" id="multi-bairro" data-rotulo="Todos os bairros">
                        <button type="button" class="form-select form-select-sm multi-botao">
                            <span class="multi-rotulo">Todos os bairros</span>
                        </button>
                        <div class="multi-menu">
                            <a href="#" class="multi-limpar">Limpar seleção</a>
                            <div class="multi-opcoes">
                                @foreach($bairros as $bairro)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="bairro[]"
                                               value="{{ $bairro }}" id="bai-{{ $loop->index }}"
                                               {{ in_array((string) $bairro, $bairrosSel, true) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size:13px;" for="bai-{{ $loop->index }}">{{ $bairro }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ESTADO (MÚLTIPLO) --}}
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Estado</label>
                    <div class="multi-filtro" id="multi-pago" data-rotulo="Todos">
                        <button type="button" class="form-select form-select-sm multi-botao">
                            <span class="multi-rotulo">Todos</span>
                        </button>
                        <div class="multi-menu">
                            <a href="#" class="multi-limpar">Limpar seleção</a>
                            <div class="multi-opcoes">
                                @foreach(['1' => 'Pago', '0' => 'Não Pago', '2' => 'Nunca Pago'] as $valor => $rotulo)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="pago[]"
                                               value="{{ $valor }}" id="pago-{{ $valor }}"
                                               {{ in_array((string) $valor, $pagoSel, true) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size:13px;" for="pago-{{ $valor }}">{{ $rotulo }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FILTRO: RECIBOS AUSENTES --}}
                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Recibos</label>
                    <select name="recibo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="rec12_ausente" {{ request('recibo') == 'rec12_ausente' ? 'selected' : '' }}>
                            Ausente Rec1 + Rec2
                        </option>
                        <option value="rec34_ausente" {{ request('recibo') == 'rec34_ausente' ? 'selected' : '' }}>
                            Ausente Rec3 + Rec4
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ url('/beneficiarios') }}" class="btn btn-outline-secondary btn-sm w-100">
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
                        <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Social ID</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Sexo</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Município</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Contacto</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Estado</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Total Recebido</th>
                        <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Acções</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiarios as $b)
                    <tr>
                        <td class="ps-4 py-3 text-muted" style="font-size:13px;">{{ $b->social_id }}</td>
                        <td class="py-3 fw-semibold">{{ $b->nome }}</td>
                        <td class="py-3">
                            @if($b->sexo == 'M')
                                <span class="badge" style="background:#eff6ff; color:#3b82f6;">M</span>
                            @elseif($b->sexo == 'F')
                                <span class="badge" style="background:#fdf2f8; color:#ec4899;">F</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted">{{ $b->municipio }}</td>
                        <td class="py-3 text-muted">{{ $b->contacto ?? '—' }}</td>
                        <td class="py-3">
                            @if($b->pago == 1)
                                <span class="badge" style="background:#f0fdf4; color:#16a34a; font-weight:600;">Pago</span>
                            @elseif($b->pago == 0)
                                <span class="badge" style="background:#fff1f2; color:#dc2626; font-weight:600;">Não Pago</span>
                            @else
                                <span class="badge" style="background:#fffbeb; color:#d97706; font-weight:600;">Nunca</span>
                            @endif
                        </td>
                        <td class="py-3 fw-semibold" style="color:#0f172a;">
                            {{ number_format($b->rec1 + $b->rec2 + $b->rec3 + $b->rec4 + $b->rec5 + $b->rec6, 0, ',', '.') }} Kz
                        </td>
                        <td class="py-3">
                            <a href="{{ url('/beneficiarios/' . $b->id) }}" class="btn btn-sm" style="background:#eff6ff; color:#3b82f6; border:none; font-weight:600; font-size:12px;">
                                <i class="bi bi-eye-fill"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
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

        timeoutId = setTimeout(() => {
            // Envia os filtros activos (incluindo seleções múltiplas) junto com o nome
            const form    = document.getElementById('form-filtro');
            const filtros = new URLSearchParams(new FormData(form));
            filtros.set('nome', termo);

            fetch(`/beneficiarios/sugestoes?${filtros.toString()}`)
                .then(res => res.json())
                .then(nomes => {
                    fecharLista();
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
                            setTimeout(() => {
                                document.getElementById('form-filtro').submit();
                            }, 50);
                        });
                        lista.appendChild(li);
                    });

                    lista.style.display = 'block';
                });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!inputNome.contains(e.target)) fecharLista();
    });

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
                setTimeout(() => {
                    document.getElementById('form-filtro').submit();
                }, 50);
            }
        } else if (e.key === 'Escape') {
            fecharLista();
        }
    });

    function fecharLista() {
        lista.innerHTML = '';
        lista.style.display = 'none';
    }

    // ===== DROPDOWNS DE SELEÇÃO MÚLTIPLA =====
    const caixas = document.querySelectorAll('.multi-filtro');

    function atualizarRotulo(caixa) {
        const marcados = caixa.querySelectorAll('input[type=checkbox]:checked');
        const rotulo   = caixa.querySelector('.multi-rotulo');

        if (marcados.length === 0) {
            rotulo.textContent = caixa.dataset.rotulo;
        } else if (marcados.length === 1) {
            rotulo.textContent = marcados[0].nextElementSibling.textContent.trim();
        } else {
            rotulo.textContent = marcados.length + ' selecionados';
        }
    }

    caixas.forEach(caixa => {
        atualizarRotulo(caixa);

        // Abrir / fechar
        caixa.querySelector('.multi-botao').addEventListener('click', function (e) {
            e.stopPropagation();
            const estavaAberta = caixa.classList.contains('aberto');
            caixas.forEach(c => c.classList.remove('aberto'));
            if (!estavaAberta) caixa.classList.add('aberto');
        });

        // Não fechar ao clicar dentro do menu
        caixa.querySelector('.multi-menu').addEventListener('click', e => e.stopPropagation());

        // Atualizar rótulo ao marcar/desmarcar
        caixa.addEventListener('change', function () {
            atualizarRotulo(caixa);
            if (caixa.id === 'multi-municipio') carregarBairros();
        });

        // Limpar seleção
        caixa.querySelector('.multi-limpar').addEventListener('click', function (e) {
            e.preventDefault();
            caixa.querySelectorAll('input[type=checkbox]').forEach(i => i.checked = false);
            atualizarRotulo(caixa);
            if (caixa.id === 'multi-municipio') carregarBairros();
        });
    });

    // Fechar ao clicar fora
    document.addEventListener('click', () => caixas.forEach(c => c.classList.remove('aberto')));

    // ===== BAIRROS DOS MUNICÍPIOS SELECIONADOS =====
    const caixaMunicipio = document.getElementById('multi-municipio');
    const caixaBairro    = document.getElementById('multi-bairro');
    const opcoesBairro   = caixaBairro.querySelector('.multi-opcoes');

    function carregarBairros() {
        const params = new URLSearchParams();
        caixaMunicipio.querySelectorAll('input:checked').forEach(i => params.append('municipio[]', i.value));

        // Guarda os bairros já marcados para manter os que continuam disponíveis
        const marcados = new Set(
            Array.from(caixaBairro.querySelectorAll('input:checked')).map(i => i.value)
        );

        opcoesBairro.innerHTML = '<span class="text-muted" style="font-size:12px;">A carregar...</span>';

        fetch(`/bairros-por-municipio?${params.toString()}`)
            .then(res => res.json())
            .then(bairros => {
                opcoesBairro.innerHTML = '';
                bairros.forEach((bairro, idx) => {
                    const div = document.createElement('div');
                    div.className = 'form-check';

                    const input = document.createElement('input');
                    input.className = 'form-check-input';
                    input.type = 'checkbox';
                    input.name = 'bairro[]';
                    input.value = bairro;
                    input.id = 'bai-' + idx;
                    input.checked = marcados.has(bairro);

                    const label = document.createElement('label');
                    label.className = 'form-check-label';
                    label.style.fontSize = '13px';
                    label.htmlFor = input.id;
                    label.textContent = bairro;

                    div.appendChild(input);
                    div.appendChild(label);
                    opcoesBairro.appendChild(div);
                });
                atualizarRotulo(caixaBairro);
            });
    }
</script>
@endpush