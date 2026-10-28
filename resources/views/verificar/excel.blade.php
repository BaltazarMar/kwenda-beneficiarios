{{-- resources/views/verificar/excel.blade.php --}}

@extends('layouts.app')

@section('titulo', 'Verificar Excel')

@section('content')

<style>
    .vx-page { font-family: 'Plus Jakarta Sans', sans-serif; }

    .vx-header {
        background: linear-gradient(135deg, #0a0f1e 0%, #1e3a6e 60%, #2E75B6 100%);
        border-radius: 16px;
        padding: 26px 32px;
        color: white;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 8px 32px rgba(10,15,30,0.18);
    }
    .vx-header-title { font-size: 1.45rem; font-weight: 800; margin: 0 0 4px; letter-spacing: -0.4px; }
    .vx-header-sub { font-size: 0.8rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 0.08em; }
    .vx-header-inst { font-size: 0.82rem; opacity: 0.75; margin-bottom: 6px; }

    .vx-upload { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .vx-file {
        background: rgba(255,255,255,0.12);
        border: 1.5px solid rgba(255,255,255,0.25);
        border-radius: 10px;
        color: white;
        padding: 7px 12px;
        font-size: 0.82rem;
        max-width: 280px;
    }
    .vx-file::file-selector-button {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 4px 10px;
        margin-right: 10px;
        cursor: pointer;
        font-weight: 700;
    }
    .vx-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px;
        font-size: 0.83rem; font-weight: 700;
        cursor: pointer; border: none;
        transition: all 0.18s ease; text-decoration: none;
    }
    .vx-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,0.22); }
    .vx-btn-green { background: #16a34a; color: white; }
    .vx-btn-green:hover { background: #15803d; color: white; }
    .vx-btn-cyan { background: #0ea5e9; color: white; }
    .vx-btn-cyan:hover { background: #0284c7; color: white; }
    .vx-btn-white { background: white; color: #1e3a6e; }

    .vx-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
    @media(max-width:992px){ .vx-stats{ grid-template-columns: repeat(2,1fr); } }
    @media(max-width:576px){ .vx-stats{ grid-template-columns: 1fr; } }
    .vx-stat {
        border-radius: 14px; padding: 20px 22px; color: white;
        position: relative; overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    .vx-stat::after {
        content: ''; position: absolute; top: -18px; right: -18px;
        width: 72px; height: 72px; border-radius: 50%; background: rgba(255,255,255,0.1);
    }
    .vx-stat-total    { background: linear-gradient(135deg, #1e40af, #3b82f6); }
    .vx-stat-novo     { background: linear-gradient(135deg, #15803d, #22c55e); }
    .vx-stat-possivel { background: linear-gradient(135deg, #92400e, #f59e0b); }
    .vx-stat-dupli    { background: linear-gradient(135deg, #991b1b, #ef4444); }
    .vx-stat-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; opacity: 0.8; margin-bottom: 6px; }
    .vx-stat-num { font-size: 2.4rem; font-weight: 800; line-height: 1; margin-bottom: 3px; }
    .vx-stat-desc { font-size: 0.75rem; opacity: 0.7; }

    .vx-alert { border-radius: 12px; border: none; padding: 13px 18px; margin-bottom: 18px; font-size: 0.88rem; font-weight: 500; }

    .vx-toolbar {
        display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: space-between;
        padding: 12px 18px; background: white; border-radius: 10px;
        border: 1px solid var(--border); margin-bottom: 18px;
        font-size: 0.8rem; font-weight: 500; color: #475569;
    }
    .vx-filtros { display: flex; gap: 8px; flex-wrap: wrap; }
    .vx-filtro {
        border: 1px solid #e2e8f0; background: #f8fafc; color: #475569;
        border-radius: 20px; padding: 5px 14px; font-size: 0.78rem; font-weight: 700; cursor: pointer;
    }
    .vx-filtro.ativo { background: #0a0f1e; color: white; border-color: #0a0f1e; }
    .vx-acoes { display: flex; gap: 10px; flex-wrap: wrap; }

    .vx-card { background: white; border-radius: 14px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
    .vx-card-header {
        background: #0a0f1e; color: white; padding: 15px 24px;
        display: flex; align-items: center; justify-content: space-between;
        font-weight: 700; font-size: 0.9rem;
    }
    .vx-count {
        background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.75);
        padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
    }
    .vx-table { width: 100%; border-collapse: collapse; }
    .vx-table thead tr { background: #f8fafc; }
    .vx-table thead th {
        padding: 11px 14px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.07em; color: #64748b; border-bottom: 2px solid #e2e8f0; white-space: nowrap;
    }
    .vx-table tbody td {
        padding: 12px 14px; border-bottom: 1px solid #f1f5f9;
        vertical-align: middle; font-size: 0.875rem; color: #1e293b;
    }
    .vx-table tbody tr:last-child td { border-bottom: none; }
    .row-novo td     { background: #f0fdf4; }
    .row-possivel td { background: #fffbeb; }
    .row-dupli td    { background: #fef2f2; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 20px;
        font-size: 0.73rem; font-weight: 700; white-space: nowrap;
    }
    .status-novo     { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-possivel { background: #fef9c3; color: #92400e; border: 1px solid #fde68a; }
    .status-dupli    { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .td-nome { font-weight: 700; color: #0f172a; }
    .td-small { font-size: 0.78rem; color: #94a3b8; }
</style>

<div class="vx-page">

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert vx-alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert vx-alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @error('ficheiro')
        <div class="alert vx-alert alert-danger alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @enderror

    {{-- Cabeçalho + upload --}}
    <div class="vx-header">
        <div>
            <div class="vx-header-inst">
                <i class="bi bi-building me-1"></i> FAS — Instituto de Desenvolvimento Local
            </div>
            <div class="vx-header-title">
                <i class="bi bi-file-earmark-excel me-2"></i>Verificar Excel
            </div>
            <div class="vx-header-sub">Detectar duplicados no Urbano e no Rural</div>
        </div>
        <form action="{{ route('verificar.processar') }}" method="POST" enctype="multipart/form-data" class="vx-upload" style="margin:0;">
            @csrf
            <input type="file" name="ficheiro" class="vx-file" accept=".xlsx,.xls,.csv" required>
            <button type="submit" class="vx-btn vx-btn-white">
                <i class="bi bi-search"></i> Verificar
            </button>
        </form>
    </div>

    @if(!empty($linhas))

        {{-- Cards de estatísticas --}}
        <div class="vx-stats">
            <div class="vx-stat vx-stat-total">
                <div class="vx-stat-label"><i class="bi bi-list-ol me-1"></i>Total no ficheiro</div>
                <div class="vx-stat-num">{{ $total }}</div>
                <div class="vx-stat-desc">linhas com nome</div>
            </div>
            <div class="vx-stat vx-stat-novo">
                <div class="vx-stat-label"><i class="bi bi-person-plus me-1"></i>Novos</div>
                <div class="vx-stat-num">{{ $novos }}</div>
                <div class="vx-stat-desc">prontos para importar</div>
            </div>
            <div class="vx-stat vx-stat-possivel">
                <div class="vx-stat-label"><i class="bi bi-exclamation-triangle me-1"></i>Possíveis Duplicados</div>
                <div class="vx-stat-num">{{ $possiveis }}</div>
                <div class="vx-stat-desc">mesmo nome, data diferente</div>
            </div>
            <div class="vx-stat vx-stat-dupli">
                <div class="vx-stat-label"><i class="bi bi-x-circle me-1"></i>Duplicados</div>
                <div class="vx-stat-num">{{ $duplicados }}</div>
                <div class="vx-stat-desc">nome + data de nascimento iguais</div>
            </div>
        </div>

        {{-- Filtros e acções --}}
        <div class="vx-toolbar">
            <div class="vx-filtros">
                <button type="button" class="vx-filtro ativo" data-f="todos" onclick="filtrarVx('todos')">Todos</button>
                <button type="button" class="vx-filtro" data-f="novo" onclick="filtrarVx('novo')">Novos</button>
                <button type="button" class="vx-filtro" data-f="possivel" onclick="filtrarVx('possivel')">Possíveis</button>
                <button type="button" class="vx-filtro" data-f="duplicado" onclick="filtrarVx('duplicado')">Duplicados</button>
            </div>
            <div class="vx-acoes">
                <a href="{{ route('verificar.exportar', $token) }}" class="vx-btn vx-btn-cyan">
                    <i class="bi bi-file-earmark-excel-fill"></i> Exportar resultado
                </a>
                <form action="{{ route('verificar.importar', $token) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="vx-btn vx-btn-green"
                        onclick="return confirm('Importar os {{ $novos }} beneficiários NOVOS para o Kwenda Urbano?')">
                        <i class="bi bi-check-circle-fill"></i> Importar Novos ({{ $novos }})
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="vx-card">
            <div class="vx-card-header">
                <span><i class="bi bi-table me-2"></i>{{ $nomeFicheiro }}</span>
                <span class="vx-count">{{ $total }} registos</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="vx-table" id="tabela-vx">
                    <thead>
                        <tr>
                            <th>Linha</th>
                            <th>Status</th>
                            <th>Nome</th>
                            <th>Data Nasc.</th>
                            <th>Sexo</th>
                            <th>Bairro</th>
                            <th>Zona</th>
                            <th>Telefone</th>
                            <th>Categoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linhas as $l)
                            @php
                                $rowClass = match($l['status']) {
                                    'duplicado' => 'row-dupli',
                                    'possivel'  => 'row-possivel',
                                    default     => 'row-novo',
                                };
                            @endphp
                            <tr class="{{ $rowClass }}" data-status="{{ $l['status'] }}">
                                <td class="td-small">{{ $l['linha'] }}</td>
                                <td>
                                    @if($l['status'] === 'duplicado')
                                        <span class="status-badge status-dupli">
                                            <i class="bi bi-x-circle-fill"></i> Duplicado{{ !empty($l['encontrado_em']) ? ' (' . $l['encontrado_em'] . ')' : '' }}
                                        </span>
                                    @elseif($l['status'] === 'possivel')
                                        <span class="status-badge status-possivel">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Possível{{ !empty($l['encontrado_em']) ? ' (' . $l['encontrado_em'] . ')' : '' }}
                                        </span>
                                    @else
                                        <span class="status-badge status-novo">
                                            <i class="bi bi-check-circle-fill"></i> Novo
                                        </span>
                                    @endif
                                </td>
                                <td class="td-nome">{{ $l['nome'] }}</td>
                                <td>
                                    @if($l['data_nascimento'])
                                        {{ $l['data_nascimento'] }}
                                    @elseif($l['data_original'])
                                        {{ $l['data_original'] }} <span title="Data não reconhecida">⚠️</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $l['sexo'] ?? '—' }}</td>
                                <td>{{ $l['bairro'] ?? '—' }}</td>
                                <td>{{ $l['zona'] ?? '—' }}</td>
                                <td>{{ $l['telefone'] ?? '—' }}</td>
                                <td>{{ $l['categoria'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>

<script>
    function filtrarVx(estado) {
        document.querySelectorAll('#tabela-vx tbody tr[data-status]').forEach(function (tr) {
            tr.style.display = (estado === 'todos' || tr.dataset.status === estado) ? '' : 'none';
        });
        document.querySelectorAll('.vx-filtro').forEach(function (b) {
            b.classList.toggle('ativo', b.dataset.f === estado);
        });
    }
</script>

@endsection