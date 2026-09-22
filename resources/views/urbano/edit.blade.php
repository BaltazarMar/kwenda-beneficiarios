@extends('layouts.app')

@section('titulo', 'Editar Beneficiário')

@section('content')

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar Beneficiário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">{{ $beneficiario->nome }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/urbano-beneficiarios/' . $beneficiario->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Corrige os seguintes erros:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('/urbano-beneficiarios/' . $beneficiario->id) }}" method="POST">
    @csrf
    @method('PUT')

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
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Social ID</label>
                        <input type="text" name="social_id" class="form-control" value="{{ old('social_id', $beneficiario->social_id) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Identificador</label>
                        <input type="text" name="identificador" class="form-control" value="{{ old('identificador', $beneficiario->identificador) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Nome *</label>
                        <input type="text" name="nome" class="form-control" value="{{ old('nome', $beneficiario->nome) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="">—</option>
                            <option value="M" @selected(old('sexo', $beneficiario->sexo) == 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo', $beneficiario->sexo) == 'F')>Feminino</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Data Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control" value="{{ old('data_nascimento', optional($beneficiario->data_nascimento)->format('Y-m-d')) }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:12px;">Tipo Documento</label>
                            <input type="text" name="tipo_documento" class="form-control" value="{{ old('tipo_documento', $beneficiario->tipo_documento) }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:12px;">Nº Documento</label>
                            <input type="text" name="numero_documento" class="form-control" value="{{ old('numero_documento', $beneficiario->numero_documento) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Profissão</label>
                        <input type="text" name="profissao" class="form-control" value="{{ old('profissao', $beneficiario->profissao) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Categoria</label>
                        <input type="text" name="categoria" class="form-control" value="{{ old('categoria', $beneficiario->categoria) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Card ID</label>
                        <input type="text" name="card_id" class="form-control" value="{{ old('card_id', $beneficiario->card_id) }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">IP1</label>
                        <input type="text" name="ip1" class="form-control" value="{{ old('ip1', $beneficiario->ip1) }}">
                    </div>
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
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $beneficiario->telefone) }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Contacto</label>
                                <input type="text" name="contacto" class="form-control" value="{{ old('contacto', $beneficiario->contacto) }}">
                            </div>
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
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Município (cadastro)</label>
                                <input type="text" name="municipio" class="form-control" value="{{ old('municipio', $beneficiario->municipio) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Bairro</label>
                                <input type="text" name="bairro" class="form-control" value="{{ old('bairro', $beneficiario->bairro) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Província (residência)</label>
                                <input type="text" name="provincia_residencia" class="form-control" value="{{ old('provincia_residencia', $beneficiario->provincia_residencia) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Município (residência)</label>
                                <input type="text" name="municipio_residencia" class="form-control" value="{{ old('municipio_residencia', $beneficiario->municipio_residencia) }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label text-muted fw-semibold" style="font-size:12px;">Comuna</label>
                                <input type="text" name="comuna" class="form-control" value="{{ old('comuna', $beneficiario->comuna) }}">
                            </div>
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
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Número da Conta</label>
                        <input type="text" name="numero_da_conta" class="form-control" value="{{ old('numero_da_conta', $beneficiario->numero_da_conta) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Número Administrativo</label>
                        <input type="text" name="numero_administrativo" class="form-control" value="{{ old('numero_administrativo', $beneficiario->numero_administrativo) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Agência</label>
                        <input type="text" name="agencia" class="form-control" value="{{ old('agencia', $beneficiario->agencia) }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Coordenada Bancária</label>
                        <input type="text" name="coordenada_bancaria" class="form-control" value="{{ old('coordenada_bancaria', $beneficiario->coordenada_bancaria) }}">
                    </div>
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
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Estado</label>
                        <select name="pago" class="form-select">
                            <option value="">—</option>
                            <option value="sim" @selected(old('pago', $beneficiario->pago) == 'sim')>Pago</option>
                            <option value="nao" @selected(old('pago', $beneficiario->pago) == 'nao')>Não Pago</option>
                            <option value="nunca" @selected(old('pago', $beneficiario->pago) == 'nunca')>Inelegível</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:12px;">Valor (Kz)</label>
                            <input type="number" step="0.01" name="valor1" class="form-control" value="{{ old('valor1', $beneficiario->valor1) }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:12px;">Data Pagamento</label>
                            <input type="date" name="data1" class="form-control" value="{{ old('data1', optional($beneficiario->data1)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size:12px;">Data de Inscrição</label>
                        <input type="date" name="data_inscricao" class="form-control" value="{{ old('data_inscricao', optional($beneficiario->data_inscricao)->format('Y-m-d')) }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:12px;">Valor Agregado (Kz)</label>
                            <input type="number" step="0.01" name="rece_valor_agregado" class="form-control" value="{{ old('rece_valor_agregado', $beneficiario->rece_valor_agregado) }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:12px;">Nome do Valor Agregado</label>
                            <input type="text" name="nome_valor_agregado" class="form-control" value="{{ old('nome_valor_agregado', $beneficiario->nome_valor_agregado) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- OBSERVAÇÃO --}}
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
                <div class="card-body pt-0">
                    <textarea name="observacao" class="form-control" rows="3">{{ old('observacao', $beneficiario->observacao) }}</textarea>
                </div>
            </div>
        </div>

        {{-- AÇÕES --}}
        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ url('/urbano-beneficiarios/' . $beneficiario->id) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary fw-semibold">
                <i class="bi bi-check-lg"></i> Guardar Alterações
            </button>
        </div>

    </div>
</form>

@endsection