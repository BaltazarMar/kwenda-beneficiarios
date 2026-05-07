{{-- resources/views/kobo/sync.blade.php --}}

@extends('layouts.app')

@section('titulo', 'Sincronização KoBoToolbox')

@section('content')

<style>
    .kobo-page { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Cabeçalho ── */
    .kobo-header {
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
    .kobo-header-title {
        font-size: 1.45rem;
        font-weight: 800;
        margin: 0 0 4px;
        letter-spacing: -0.4px;
    }
    .kobo-header-sub {
        font-size: 0.8rem;
        opacity: 0.6;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .kobo-header-inst {
        font-size: 0.82rem;
        opacity: 0.75;
        margin-bottom: 6px;
    }
    .kobo-btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    .kobo-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.83rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.18s ease;
        text-decoration: none;
        letter-spacing: 0.01em;
    }
    .kobo-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,0.22); }
    .kobo-btn-ghost  { background: rgba(255,255,255,0.12); color: white; border: 1.5px solid rgba(255,255,255,0.25); }
    .kobo-btn-ghost:hover  { background: rgba(255,255,255,0.22); color: white; }
    .kobo-btn-green  { background: #16a34a; color: white; }
    .kobo-btn-green:hover  { background: #15803d; color: white; }
    .kobo-btn-cyan   { background: #0ea5e9; color: white; }
    .kobo-btn-cyan:hover   { background: #0284c7; color: white; }

    /* ── Cards de estatísticas ── */
    .kobo-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    @media(max-width:992px){ .kobo-stats{ grid-template-columns: repeat(2,1fr); } }
    @media(max-width:576px){ .kobo-stats{ grid-template-columns: 1fr; } }

    .kobo-stat {
        border-radius: 14px;
        padding: 20px 22px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        transition: transform 0.18s, box-shadow 0.18s;
    }
    .kobo-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,0.15); }
    .kobo-stat::after {
        content: '';
        position: absolute;
        top: -18px; right: -18px;
        width: 72px; height: 72px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    .kobo-stat-total   { background: linear-gradient(135deg, #1e40af, #3b82f6); }
    .kobo-stat-novo    { background: linear-gradient(135deg, #15803d, #22c55e); }
    .kobo-stat-possivel{ background: linear-gradient(135deg, #92400e, #f59e0b); }
    .kobo-stat-dupli   { background: linear-gradient(135deg, #991b1b, #ef4444); }
    .kobo-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        opacity: 0.8;
        margin-bottom: 6px;
    }
    .kobo-stat-num {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 3px;
    }
    .kobo-stat-desc { font-size: 0.75rem; opacity: 0.7; }

    /* ── Alertas ── */
    .kobo-alert {
        border-radius: 12px;
        border: none;
        padding: 13px 18px;
        margin-bottom: 18px;
        font-size: 0.88rem;
        font-weight: 500;
    }

    /* ── Legenda ── */
    .kobo-legenda {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: center;
        padding: 12px 18px;
        background: white;
        border-radius: 10px;
        border: 1px solid var(--border);
        margin-bottom: 18px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #475569;
    }
    .kobo-legenda-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        flex-shrink: 0;
    }

    /* ── Tabela ── */
    .kobo-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .kobo-card-header {
        background: #0a0f1e;
        color: white;
        padding: 15px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .kobo-card-header span.count-badge {
        background: rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.75);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .kobo-table { width: 100%; border-collapse: collapse; }
    .kobo-table thead tr { background: #f8fafc; }
    .kobo-table thead th {
        padding: 11px 14px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .kobo-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.875rem;
        color: #1e293b;
    }
    .kobo-table tbody tr:last-child td { border-bottom: none; }
    .kobo-table tbody tr:hover td { filter: brightness(0.97); }

    .row-novo td     { background: #f0fdf4; }
    .row-possivel td { background: #fffbeb; }
    .row-dupli td    { background: #fef2f2; }

    /* ── Badges de status ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .status-novo     { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-possivel { background: #fef9c3; color: #92400e; border: 1px solid #fde68a; }
    .status-dupli    { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* ── Nome e textos ── */
    .td-nome { font-weight: 700; color: #0f172a; }
    .td-small { font-size: 0.78rem; color: #94a3b8; }
    .td-muted { color: #cbd5e1; font-size: 0.85rem; }

    /* ── Botão eliminar individual ── */
    .btn-del {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 8px;
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-del:hover { background: #be123c; color: white; border-color: #be123c; }

    /* ── Estado vazio ── */
    .kobo-empty {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .kobo-empty-icon { font-size: 3rem; margin-bottom: 12px; }
    .kobo-empty-title { font-weight: 700; font-size: 1rem; margin-bottom: 4px; color: #475569; }
    .kobo-empty-desc  { font-size: 0.85rem; }
</style>

<div class="kobo-page">

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert kobo-alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert kobo-alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cabeçalho --}}
    <div class="kobo-header">
        <div>
            <div class="kobo-header-inst">
                <i class="bi bi-building me-1"></i> FAS — Instituto de Desenvolvimento Local
            </div>
            <div class="kobo-header-title">
                <i class="bi bi-arrow-repeat me-2"></i>Sincronização KoBoToolbox
            </div>
            <div class="kobo-header-sub">Kwenda Urbano — Lunda Sul 2026</div>
        </div>
        <div class="kobo-btn-group">
            <button onclick="location.reload()" class="kobo-btn kobo-btn-ghost">
                <i class="bi bi-arrow-clockwise"></i> Actualizar
                
            </button>
            {{-- Limpar todos --}}
@if($total > 0)
<form action="{{ route('kobo.limpar.todos') }}" method="POST" style="margin:0;">
    @csrf
    <button type="submit" class="kobo-btn"
        style="background:#1e293b; color:#f87171; border:1.5px solid rgba(248,113,113,0.3);"
        onclick="return confirm('⚠️ Tens a certeza? Isto vai eliminar TODOS os {{ $total }} registos do KoBoToolbox sem excepção!')">
        <i class="bi bi-trash3-fill"></i> Limpar Tudo ({{ $total }})
    </button>
</form>
@endif
            <form action="{{ route('kobo.importar') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="kobo-btn kobo-btn-green"
                    onclick="return confirm('Importar os {{ $novos }} beneficiários NOVOS para a base de dados?')">
                    <i class="bi bi-check-circle-fill"></i> Importar Novos ({{ $novos }})
                </button>
            </form>
            <a href="{{ route('kobo.exportar') }}" class="kobo-btn kobo-btn-cyan">
                <i class="bi bi-file-earmark-excel-fill"></i> Exportar Excel
            </a>
        </div>
    </div>

    {{-- Cards de estatísticas --}}
    <div class="kobo-stats">
        <div class="kobo-stat kobo-stat-total">
            <div class="kobo-stat-label"><i class="bi bi-cloud-download me-1"></i>Total KoBoToolbox</div>
            <div class="kobo-stat-num">{{ $total }}</div>
            <div class="kobo-stat-desc">submissões recebidas</div>
        </div>
        <div class="kobo-stat kobo-stat-novo">
            <div class="kobo-stat-label"><i class="bi bi-person-plus me-1"></i>Novos Beneficiários</div>
            <div class="kobo-stat-num">{{ $novos }}</div>
            <div class="kobo-stat-desc">prontos para importar</div>
        </div>
        <div class="kobo-stat kobo-stat-possivel">
            <div class="kobo-stat-label"><i class="bi bi-exclamation-triangle me-1"></i>Possíveis Duplicados</div>
            <div class="kobo-stat-num">{{ $possiveis }}</div>
            <div class="kobo-stat-desc">mesmo nome, data diferente</div>
        </div>
        <div class="kobo-stat kobo-stat-dupli">
            <div class="kobo-stat-label"><i class="bi bi-x-circle me-1"></i>Duplicados</div>
            <div class="kobo-stat-num">{{ $duplicados }}</div>
            <div class="kobo-stat-desc">nome + data de nascimento iguais</div>
        </div>
    </div>

    {{-- Legenda --}}
    <div class="kobo-legenda">
        <span><span class="kobo-legenda-dot" style="background:#22c55e;"></span>Novo — beneficiário único</span>
        <span><span class="kobo-legenda-dot" style="background:#f59e0b;"></span>Possível — mesmo nome, data diferente</span>
        <span><span class="kobo-legenda-dot" style="background:#ef4444;"></span>Duplicado — nome + data de nascimento iguais</span>
    </div>

    {{-- Tabela --}}
    <div class="kobo-card">
        <div class="kobo-card-header">
            <span><i class="bi bi-table me-2"></i>Submissões do KoBoToolbox</span>
            <span class="count-badge">{{ $total }} registos</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="kobo-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Nome</th>
                        <th>Data Nasc.</th>
                        <th>Categoria</th>
                        <th>Município</th>
                        <th>Bairro</th>
                        <th>Instituição</th>
                        <th>Técnico</th>
                        <th>Submetido em</th>
                        <th>Acção</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissionsComStatus as $sub)
                        @php
                            $rowClass = match($sub['status']) {
                                'duplicado' => 'row-dupli',
                                'possivel'  => 'row-possivel',
                                default     => 'row-novo',
                            };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>
                                @if($sub['status'] === 'duplicado')
                                    <span class="status-badge status-dupli">
                                        <i class="bi bi-x-circle-fill"></i> Duplicado
                                    </span>
                                @elseif($sub['status'] === 'possivel')
                                    <span class="status-badge status-possivel">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Possível
                                    </span>
                                @else
                                    <span class="status-badge status-novo">
                                        <i class="bi bi-check-circle-fill"></i> Novo
                                    </span>
                                @endif
                            </td>
                            <td class="td-nome">{{ $sub['nome'] ?? '—' }}</td>
                            <td>{{ $sub['data_nascimento'] ?? '—' }}</td>
                            <td>{{ $sub['categoria'] ?? '—' }}</td>
                            <td>{{ $sub['municipio'] ?? '—' }}</td>
                            <td>{{ $sub['bairro'] ?? '—' }}</td>
                            <td class="td-small">{{ $sub['instituicao'] ?? '—' }}</td>
                            <td>{{ $sub['tecnico'] ?? '—' }}</td>
                            <td class="td-small">
                                {{ $sub['data_submissao'] ? \Carbon\Carbon::parse($sub['data_submissao'])->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td>
                                @if($sub['status'] === 'duplicado' || $sub['status'] === 'possivel')
                                    <form action="{{ route('kobo.eliminar.individual') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="kobo_id" value="{{ $sub['kobo_id'] }}">
                                        <button type="submit" class="btn-del"
                                            onclick="return confirm('Eliminar este registo do KoBoToolbox?')">
                                            <i class="bi bi-trash3-fill"></i> Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="td-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="kobo-empty">
                                    <div class="kobo-empty-icon">📭</div>
                                    <div class="kobo-empty-title">Nenhuma submissão encontrada</div>
                                    <div class="kobo-empty-desc">Aguarda que as instituições preencham o formulário</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection