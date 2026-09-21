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
        {{-- Ajusta a rota se o nome/prefixo usado no teu sistema for diferente --}}
        <a href="{{ url('/beneficiarios-urbano/' . $beneficiario->id . '/edit') }}" class="btn btn-sm btn-warning fw-semibold">
            <i class="bi bi-pencil-fill"></i> Editar
        </a>
        <a href="{{ url('/beneficiarios-urbano') }}" class="btn btn-sm btn-outline-secondary">
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
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Identificador</th>
                        <td class="py-2">{{ $beneficiario->identificador ?? '—' }}</td>
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
                        <td class="py-2">{{ $beneficiario->data_nascimento ? \Carbon\Carbon::parse($beneficiario->data_nascimento)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Documento</th>
                        <td class="py-2">
                            {{ $beneficiario->tipo_documento ?? '—' }}
                            @if($beneficiario->numero_documento)
                                — {{ $beneficiario->numero_documento }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Profissão</th>
                        <td class="py-2">{{ $beneficiario->profissao ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Categoria</th>
                        <td class="py-2">{{ $beneficiario->categoria ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Card ID</th>
                        <td class="py-2">{{ $beneficiario->card_id ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- CONTACTO + LOCALIZAÇÃO --}}
    <div class="col-12 col-md-6">
        <div class="row g-4">

            {{-- CONTACTO --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pb-0" style="background:transparent;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:32px; height:32px; background:#f5f3ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-telephone-fill" style="color:#7c3aed;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:14px;">Contacto</span>
                        </div>
                        <hr class="mt-0">
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-borderless mb-0" style="font-size:14px;">
                            <tr>
                                <th width="40%" class="text-muted fw-semibold py-2" style="font-size:12px;">Telefone</th>
                                <td class="py-2">{{ $beneficiario->telefone ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Contacto</th>
                                <td class="py-2">{{ $beneficiario->contacto ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

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
                                <th width="40%" class="text-muted fw-semibold py-2" style="font-size:12px;">Município (cadastro)</th>
                                <td class="py-2">{{ $beneficiario->municipio ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Bairro</th>
                                <td class="py-2">{{ $beneficiario->bairro ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Província (residência)</th>
                                <td class="py-2">{{ $beneficiario->provincia_residencia ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Município (residência)</th>
                                <td class="py-2">{{ $beneficiario->municipio_residencia ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold py-2" style="font-size:12px;">Comuna</th>
                                <td class="py-2">{{ $beneficiario->comuna ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- DADOS BANCÁRIOS --}}
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#f0f9ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-bank" style="color:#0284c7;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Dados Bancários</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <table class="table table-borderless mb-0" style="font-size:14px;">
                    <tr>
                        <th width="40%" class="text-muted fw-semibold py-2" style="font-size:12px;">Número da Conta</th>
                        <td class="py-2">{{ $beneficiario->numero_da_conta ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Número Administrativo</th>
                        <td class="py-2">{{ $beneficiario->numero_administrativo ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Agência</th>
                        <td class="py-2">{{ $beneficiario->agencia ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Coordenada Bancária</th>
                        <td class="py-2">{{ $beneficiario->coordenada_bancaria ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGAMENTO --}}
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#fffbeb; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-cash-stack" style="color:#d97706;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Estado de Pagamento</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        @if($beneficiario->pago == 'sim')
                            <span class="badge px-3 py-2" style="background:#f0fdf4; color:#16a34a; font-size:14px; font-weight:700;">✔ Pago</span>
                        @elseif($beneficiario->pago == 'nao')
                            <span class="badge px-3 py-2" style="background:#fff1f2; color:#dc2626; font-size:14px; font-weight:700;">✘ Não Pago</span>
                        @elseif($beneficiario->pago == 'nunca')
                            <span class="badge px-3 py-2" style="background:#fffbeb; color:#d97706; font-size:14px; font-weight:700;">⚠ Inelegível</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                    <div class="text-end">
                        <div class="text-muted" style="font-size:12px;">Valor</div>
                        <div class="fw-bold" style="font-size:22px; color:#16a34a;">
                            {{ $beneficiario->valor1 ? number_format($beneficiario->valor1, 0, ',', '.') . ' Kz' : '—' }}
                        </div>
                    </div>
                </div>
                <table class="table table-borderless mb-0" style="font-size:14px;">
                    <tr>
                        <th width="40%" class="text-muted fw-semibold py-2" style="font-size:12px;">Data do Pagamento</th>
                        <td class="py-2">{{ $beneficiario->data1 ? \Carbon\Carbon::parse($beneficiario->data1)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Data de Inscrição</th>
                        <td class="py-2">{{ $beneficiario->data_inscricao ? \Carbon\Carbon::parse($beneficiario->data_inscricao)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold py-2" style="font-size:12px;">Valor Agregado</th>
                        <td class="py-2">
                            {{ $beneficiario->rece_valor_agregado ? number_format($beneficiario->rece_valor_agregado, 0, ',', '.') . ' Kz' : '—' }}
                            @if($beneficiario->nome_valor_agregado)
                                ({{ $beneficiario->nome_valor_agregado }})
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- OBSERVAÇÃO --}}
    @if($beneficiario->observacao)
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#fdf2f8; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-chat-text-fill" style="color:#ec4899;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Observação</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0 text-muted" style="font-size:14px;">
                {{ $beneficiario->observacao }}
            </div>
        </div>
    </div>
    @endif

</div>

@endsection