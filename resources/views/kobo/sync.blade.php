{{-- resources/views/kobo/sync.blade.php --}}

@extends('layouts.app')

@section('title', 'Sincronização KoBoToolbox')

@section('content')
<div class="container-fluid px-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">🔄 Sincronização KoBoToolbox</h2>
            <small class="text-muted">Kwenda Urbano — Lunda Sul 2026</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="location.reload()" class="btn btn-outline-secondary">
                🔃 Actualizar
            </button>          

        

            </form>
          

            {{-- Importar novos --}}
            <form action="{{ route('kobo.importar') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('Importar apenas os beneficiários NOVOS (sem duplicados)?')">
                    ✅ Importar Novos ({{ $novos }})
                </button>
            </form>

            {{-- Exportar Excel --}}
            <a href="{{ route('kobo.exportar') }}" class="btn btn-info text-white">
                📥 Exportar Excel
            </a>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cards de resumo --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total no KoBoToolbox</h6>
                    <h2 class="mb-0">{{ $total }}</h2>
                    <small>submissões recebidas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">✅ Novos Beneficiários</h6>
                    <h2 class="mb-0">{{ $novos }}</h2>
                    <small>prontos para importar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-dark" style="background-color:#ffc107;">
                <div class="card-body">
                    <h6 class="card-title">⚠️ Possíveis Duplicados</h6>
                    <h2 class="mb-0">{{ $possiveis }}</h2>
                    <small>mesmo nome, data diferente</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">❌ Duplicados</h6>
                    <h2 class="mb-0">{{ $duplicados }}</h2>
                    <small>já existem na base de dados</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Legenda --}}
    <div class="mb-3">
        <span class="badge bg-success me-2">✅ Novo — beneficiário novo</span>
        <span class="badge text-dark me-2" style="background-color:#ffc107;">⚠️ Possível — mesmo nome, data diferente</span>
        <span class="badge bg-danger">❌ Duplicado — nome + data iguais</span>
    </div>

    {{-- Tabela de submissões --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Submissões do KoBoToolbox</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-dark">
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
                                    'duplicado' => 'table-danger',
                                    'possivel'  => 'table-warning',
                                    default     => 'table-success',
                                };
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="text-center">
                                    @if($sub['status'] === 'duplicado')
                                        <span class="badge bg-danger">❌ Duplicado</span>
                                    @elseif($sub['status'] === 'possivel')
                                        <span class="badge text-dark" style="background-color:#ffc107;">⚠️ Possível</span>
                                    @else
                                        <span class="badge bg-success">✅ Novo</span>
                                    @endif
                                </td>
                                <td><strong>{{ $sub['nome'] ?? '—' }}</strong></td>
                                <td>{{ $sub['data_nascimento'] ?? '—' }}</td>
                                <td>{{ $sub['categoria'] ?? '—' }}</td>
                                <td>{{ $sub['municipio'] ?? '—' }}</td>
                                <td>{{ $sub['bairro'] ?? '—' }}</td>
                                <td><small>{{ $sub['instituicao'] ?? '—' }}</small></td>
                                <td>{{ $sub['tecnico'] ?? '—' }}</td>
                                <td><small>{{ $sub['data_submissao'] ? \Carbon\Carbon::parse($sub['data_submissao'])->format('d/m/Y H:i') : '—' }}</small></td>
                                <td class="text-center">
                                    @if($sub['status'] === 'duplicado' || $sub['status'] === 'possivel')
                                        <form action="{{ route('kobo.eliminar.individual') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="kobo_id" value="{{ $sub['kobo_id'] }}">
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                title="Eliminar este registo"
                                                onclick="return confirm('Eliminar este registo do KoBoToolbox?')">
                                                🗑️
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    Nenhuma submissão encontrada no KoBoToolbox.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection