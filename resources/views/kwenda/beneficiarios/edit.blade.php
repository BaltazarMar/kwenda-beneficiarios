@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Editar Beneficiário</h4>
        <a href="{{ url('/beneficiarios/' . $beneficiario->id) }}" class="btn btn-outline-secondary">
            ⬅ Voltar ao detalhe
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ url('/beneficiarios/' . $beneficiario->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- DADOS PESSOAIS --}}
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <strong>👤 Dados Pessoais</strong>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Social ID</label>
                            <input type="text" class="form-control" value="{{ $beneficiario->social_id }}" disabled>
                            <small class="text-muted">O Social ID não pode ser alterado.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome', $beneficiario->nome) }}">
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sexo</label>
                            <select name="sexo" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="M" {{ old('sexo', $beneficiario->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo', $beneficiario->sexo) == 'F' ? 'selected' : '' }}>Feminino</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" name="data_nasc" class="form-control"
                                value="{{ old('data_nasc', $beneficiario->data_nasc) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profissão</label>
                            <input type="text" name="profissao" class="form-control"
                                value="{{ old('profissao', $beneficiario->profissao) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contacto</label>
                            <input type="text" name="contacto" class="form-control"
                                value="{{ old('contacto', $beneficiario->contacto) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Card ID</label>
                            <input type="text" name="card_id" class="form-control"
                                value="{{ old('card_id', $beneficiario->card_id) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agente</label>
                            <input type="text" name="agente" class="form-control"
                                value="{{ old('agente', $beneficiario->agente) }}">
                        </div>

                    </div>
                </div>
            </div>

            {{-- LOCALIZAÇÃO + PAGAMENTO --}}
            <div class="col-12 col-md-6">

                {{-- LOCALIZAÇÃO --}}
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <strong>📍 Localização</strong>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Província</label>
                            <input type="text" name="provincia" class="form-control"
                                value="{{ old('provincia', $beneficiario->provincia) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Município</label>
                            <input type="text" name="municipio" class="form-control"
                                value="{{ old('municipio', $beneficiario->municipio) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comuna</label>
                            <input type="text" name="comuna" class="form-control"
                                value="{{ old('comuna', $beneficiario->comuna) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" class="form-control"
                                value="{{ old('bairro', $beneficiario->bairro) }}">
                        </div>

                    </div>
                </div>

                {{-- PAGAMENTO --}}
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <strong>💰 Estado de Pagamento</strong>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Pago</label>
                            <select name="pago" class="form-select">
                                <option value="1" {{ old('pago', $beneficiario->pago) == 1 ? 'selected' : '' }}>Pago</option>
                                <option value="0" {{ old('pago', $beneficiario->pago) == 0 ? 'selected' : '' }}>Não Pago</option>
                                <option value="2" {{ old('pago', $beneficiario->pago) == 2 ? 'selected' : '' }}>Nunca Pago</option>
                            </select>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RECORRÊNCIAS --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <strong>📅 Recorrências</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @for($i = 1; $i <= 6; $i++)
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3">
                                    <h6 class="mb-3">{{ $i }}ª Recorrência</h6>
                                    <div class="mb-2">
                                        <label class="form-label">Valor (Kz)</label>
                                        <input type="number" name="rec{{ $i }}" class="form-control"
                                            value="{{ old('rec'.$i, $beneficiario->{'rec'.$i}) }}">
                                    </div>
                                    <div>
                                        <label class="form-label">Data</label>
                                        <input type="date" name="data{{ $i }}" class="form-control"
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
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <strong>📝 Observações</strong>
                    </div>
                    <div class="card-body">
                        <textarea name="observacoes" class="form-control" rows="4">{{ old('observacoes', $beneficiario->observacoes) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- BOTÕES --}}
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-5">💾 Guardar Alterações</button>
            <a href="{{ url('/beneficiarios/' . $beneficiario->id) }}" class="btn btn-outline-secondary px-5">Cancelar</a>
        </div>

    </form>

</div>
@endsection