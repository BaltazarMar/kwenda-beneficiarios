@extends('layouts.app')

@section('content')
<div class="container-fluid">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Detalhes do Beneficiário</h4>
    <div class="d-flex gap-2">
        <a href="{{ url('/beneficiarios/' . $beneficiario->id . '/edit') }}" class="btn btn-warning">
            ✏️ Editar
        </a>
        <a href="{{ url('/beneficiarios') }}" class="btn btn-outline-secondary">
            ⬅ Voltar à listagem
        </a>
    </div>
</div>

    <div class="row g-4">

        {{-- DADOS PESSOAIS --}}
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <strong>👤 Dados Pessoais</strong>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Social ID</th>
                            <td>{{ $beneficiario->social_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <td>{{ $beneficiario->nome ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Sexo</th>
                            <td>
                                @if($beneficiario->sexo == 'M')
                                    <span class="badge bg-info">Masculino</span>
                                @elseif($beneficiario->sexo == 'F')
                                    <span class="badge" style="background-color:#e91e8c;">Feminino</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Data Nascimento</th>
                            <td>{{ $beneficiario->data_nasc ? \Carbon\Carbon::parse($beneficiario->data_nasc)->format('d/m/Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <th>Profissão</th>
                            <td>{{ $beneficiario->profissao ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Contacto</th>
                            <td>{{ $beneficiario->contacto ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Card ID</th>
                            <td>{{ $beneficiario->card_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Agente</th>
                            <td>{{ $beneficiario->agente ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- LOCALIZAÇÃO --}}
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <strong>📍 Localização</strong>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Província</th>
                            <td>{{ $beneficiario->provincia ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Município</th>
                            <td>{{ $beneficiario->municipio ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Comuna</th>
                            <td>{{ $beneficiario->comuna ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Bairro</th>
                            <td>{{ $beneficiario->bairro ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAGAMENTO --}}
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <strong>💰 Estado de Pagamento</strong>
                </div>
                <div class="card-body text-center">
                    @if($beneficiario->pago == 1)
                        <span class="badge bg-success fs-5 px-4 py-2">✔ Pago</span>
                    @elseif($beneficiario->pago == 0)
                        <span class="badge bg-danger fs-5 px-4 py-2">✘ Não Pago</span>
                    @else
                        <span class="badge bg-warning text-dark fs-5 px-4 py-2">⚠ Nunca Pago</span>
                    @endif

                    <hr>
                    <h5 class="mt-2">Total Recebido</h5>
                    <h3 class="text-success">
                        {{ number_format(
                            $beneficiario->rec1 + $beneficiario->rec2 + $beneficiario->rec3 +
                            $beneficiario->rec4 + $beneficiario->rec5 + $beneficiario->rec6,
                            0, ',', '.'
                        ) }} Kz
                    </h3>
                </div>
            </div>
        </div>

        {{-- RECORRÊNCIAS --}}
        <div class="col-12 col-md-8">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <strong>📅 Recorrências</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Recorrência</th>
                                <th>Valor</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 6; $i++)
                            <tr>
                                <td>{{ $i }}ª Recorrência</td>
                                <td>
                                    @if($beneficiario->{'rec'.$i} > 0)
                                        <span class="text-success fw-bold">
                                            {{ number_format($beneficiario->{'rec'.$i}, 0, ',', '.') }} Kz
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($beneficiario->{'data'.$i})
                                        {{ \Carbon\Carbon::parse($beneficiario->{'data'.$i})->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- OBSERVAÇÕES --}}
        @if($beneficiario->observacoes)
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <strong>📝 Observações</strong>
                </div>
                <div class="card-body">
                    {{ $beneficiario->observacoes }}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection