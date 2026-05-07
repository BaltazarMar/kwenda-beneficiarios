@extends('layouts.app')

@section('titulo', 'Editar Beneficiário')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar Beneficiário</h4>
        <p class="text-muted mb-0" style="font-size:13px;">{{ $beneficiario->nome }}</p>
    </div>
    <a href="{{ url('/beneficiarios/' . $beneficiario->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar ao detalhe
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        @foreach($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ url('/beneficiarios/' . $beneficiario->id) }}">
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
                        <label class="form-label fw-semibold" style="font-size:13px;">Social ID</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $beneficiario->social_id }}" disabled>
                        <small class="text-muted">O Social ID não pode ser alterado.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nome</label>
                        <input type="text" name="nome" class="form-control form-control-sm @error('nome') is-invalid @enderror"
                            value="{{ old('nome', $beneficiario->nome) }}">
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Sexo</label>
                        <select name="sexo" class="form-select form-select-sm">
                            <option value="">— Seleccionar —</option>
                            <option value="M" {{ old('sexo', $beneficiario->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo', $beneficiario->sexo) == 'F' ? 'selected' : '' }}>Feminino</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Data de Nascimento</label>
                        <input type="date" name="data_nasc" class="form-control form-control-sm"
                            value="{{ old('data_nasc', $beneficiario->data_nasc) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Profissão</label>
                        <input type="text" name="profissao" class="form-control form-control-sm"
                            value="{{ old('profissao', $beneficiario->profissao) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Contacto</label>
                        <input type="text" name="contacto" class="form-control form-control-sm"
                            value="{{ old('contacto', $beneficiario->contacto) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Card ID</label>
                        <input type="text" name="card_id" class="form-control form-control-sm"
                            value="{{ old('card_id', $beneficiario->card_id) }}">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size:13px;">Agente</label>
                        <input type="text" name="agente" class="form-control form-control-sm"
                            value="{{ old('agente', $beneficiario->agente) }}">
                    </div>

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
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px;">Província</label>
                                <input type="text" name="provincia" class="form-control form-control-sm"
                                    value="{{ old('provincia', $beneficiario->provincia) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px;">Município</label>
                                <input type="text" name="municipio" class="form-control form-control-sm"
                                    value="{{ old('municipio', $beneficiario->municipio) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px;">Comuna</label>
                                <input type="text" name="comuna" class="form-control form-control-sm"
                                    value="{{ old('comuna', $beneficiario->comuna) }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold" style="font-size:13px;">Bairro</label>
                                <input type="text" name="bairro" class="form-control form-control-sm"
                                    value="{{ old('bairro', $beneficiario->bairro) }}">
                            </div>
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
                        <div class="card-body pt-0">
                            <label class="form-label fw-semibold" style="font-size:13px;">Pago</label>
                            <select name="pago" class="form-select form-select-sm">
                                <option value="1" {{ old('pago', $beneficiario->pago) == 1 ? 'selected' : '' }}>Pago</option>
                                <option value="0" {{ old('pago', $beneficiario->pago) == 0 ? 'selected' : '' }}>Não Pago</option>
                                <option value="2" {{ old('pago', $beneficiario->pago) == 2 ? 'selected' : '' }}>Nunca Pago</option>
                            </select>
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
                <div class="card-body pt-0">
                    <div class="row g-3">
                        @for($i = 1; $i <= 6; $i++)
                        <div class="col-12 col-md-4">
                            <div class="p-3" style="background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0;">
                                <p class="fw-bold mb-3" style="font-size:13px; color:#0f172a;">{{ $i }}ª Recorrência</p>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Valor (Kz)</label>
                                    <input type="number" name="rec{{ $i }}" class="form-control form-control-sm"
                                        value="{{ old('rec'.$i, $beneficiario->{'rec'.$i}) }}">
                                </div>
                                <div>
                                    <label class="form-label fw-semibold" style="font-size:12px; color:#64748b;">Data</label>
                                    <input type="date" name="data{{ $i }}" class="form-control form-control-sm"
                                        value="{{ old('data'.$i, $beneficiario->{'data'.$i}) }}">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- OBSERVAÇÕES --}}
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
                <div class="card-body pt-0">
                    <textarea name="observacoes" class="form-control form-control-sm" rows="4"
                        placeholder="Adicionar observações...">{{ old('observacoes', $beneficiario->observacoes) }}</textarea>
                </div>
            </div>
        </div>

    </div>

    {{-- BOTÕES --}}
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-5">
            <i class="bi bi-floppy-fill"></i> Guardar Alterações
        </button>
        <a href="{{ url('/beneficiarios/' . $beneficiario->id) }}" class="btn btn-outline-secondary px-5">Cancelar</a>
    </div>

</form>

@endsection