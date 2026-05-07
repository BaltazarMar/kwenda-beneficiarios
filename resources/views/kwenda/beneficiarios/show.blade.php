@extends('layouts.app')

@section('titulo', 'Detalhes do Beneficiário')

@section('content')

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Detalhes do Beneficiário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">{{ $beneficiario->nome }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/beneficiarios/' . $beneficiario->id . '/edit') }}" class="btn btn-sm btn-warning fw-semibold">
            <i class="bi bi-pencil-fill"></i> Editar
        </a>
        <a href="{{ url('/beneficiarios') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- DADOS PESSOAIS --}}
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-person-fill" style="color:#3b82f6;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Dados Pessoais</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <table class="table table-borderless mb-0" style="font-size:14px;">
                    <tr>
                        <th width="40%" class="text-muted fw-semibold py-2" style="font-size:12px;">Social ID</th>
                        <td class="py-2 fw-semibold">{{ $beneficiario->social_id ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Nome</th>
                        <td class="py-2">{{ $beneficiario->nome ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Sexo</th>
                        <td class="py-2">
                            @if($beneficiario->sexo == 'M')
                                <span class="badge" style="background:#eff6ff; color:#3b82f6;">Masculino</span>
                            @elseif($beneficiario->sexo == 'F')
                                <span class="badge" style="background:#fdf2f8; color:#ec4899;">Feminino</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Data Nascimento</th>
                        <td class="py-2">{{ $beneficiario->data_nasc ? \Carbon\Carbon::parse($beneficiario->data_nasc)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Profissão</th>
                        <td class="py-2">{{ $beneficiario->profissao ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Contacto</th>
                        <td class="py-2">{{ $beneficiario->contacto ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Card ID</th>
                        <td class="py-2">{{ $beneficiario->card_id ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Agente</th>
                        <td class="py-2">{{ $beneficiario->agente ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- LOCALIZAÇÃO + PAGAMENTO --}}
    <div class="col-12 col-md-6">
        <div class="row g-4">

            {{-- LOCALIZAÇÃO --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pb-0" style="background:transparent;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:32px; height:32px; background:#f0fdf4; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-geo-alt-fill" style="color:#16a34a;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:14px;">Localização</span>
                        </div>
                        <hr class="mt-0">
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-borderless mb-0" style="font-size:14px;">
                            <tr>
                                <th width="40%" class="text-muted fw-semibold py-2" style="font-size:12px;">Província</th>
                                <td class="py-2">{{ $beneficiario->provincia ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Município</th>
                                <td class="py-2">{{ $beneficiario->municipio ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Comuna</th>
                                <td class="py-2">{{ $beneficiario->comuna ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Bairro</th>
                                <td class="py-2">{{ $beneficiario->bairro ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PAGAMENTO --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pb-0" style="background:transparent;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:32px; height:32px; background:#fffbeb; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-cash-stack" style="color:#d97706;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:14px;">Estado de Pagamento</span>
                        </div>
                        <hr class="mt-0">
                    </div>
                    <div class="card-body pt-0 d-flex align-items-center justify-content-between">
                        <div>
                            @if($beneficiario->pago == 1)
                                <span class="badge px-3 py-2" style="background:#f0fdf4; color:#16a34a; font-size:14px; font-weight:700;">✔ Pago</span>
                            @elseif($beneficiario->pago == 0)
                                <span class="badge px-3 py-2" style="background:#fff1f2; color:#dc2626; font-size:14px; font-weight:700;">✘ Não Pago</span>
                            @else
                                <span class="badge px-3 py-2" style="background:#fffbeb; color:#d97706; font-size:14px; font-weight:700;">⚠ Nunca Pago</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="text-muted" style="font-size:12px;">Total Recebido</div>
                            <div class="fw-bold" style="font-size:22px; color:#16a34a;">
                                {{ number_format(
                                    $beneficiario->rec1 + $beneficiario->rec2 + $beneficiario->rec3 +
                                    $beneficiario->rec4 + $beneficiario->rec5 + $beneficiario->rec6,
                                    0, ',', '.'
                                ) }} Kz
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- RECORRÊNCIAS --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-calendar-check-fill" style="color:#3b82f6;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Recorrências</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0" style="font-size:14px;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="ps-4 py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Recorrência</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Valor</th>
                            <th class="py-3" style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= 6; $i++)
                        <tr>
                            <td class="ps-4 py-3 fw-semibold">{{ $i }}ª Recorrência</td>
                            <td class="py-3">
                                @if($beneficiario->{'rec'.$i} > 0)
                                    <span class="fw-bold" style="color:#16a34a;">
                                        {{ number_format($beneficiario->{'rec'.$i}, 0, ',', '.') }} Kz
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3 text-muted">
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
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#fdf2f8; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-chat-text-fill" style="color:#ec4899;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Observações</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0 text-muted" style="font-size:14px;">
                {{ $beneficiario->observacoes }}
            </div>
        </div>
    </div>
    @endif

</div>

@endsection