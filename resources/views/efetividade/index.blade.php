@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4>Folha de Efetividade</h4>
            <small>Controlo de presenças, faltas e férias</small>
        </div>
    </div>

    {{-- FORM --}}
    <form action="/efetividade/pdf" method="POST">
        @csrf

        {{-- FILTROS --}}
        <div class="row mb-3">

            <div class="col-md-3">
                <label>Data Início</label>
                <input type="date" name="inicio" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Data Fim</label>
                <input type="date" name="fim" class="form-control" required>
            </div>

            {{-- BOTÕES --}}
            <div class="col-md-6 d-flex align-items-end justify-content-end">

                <button type="submit" class="btn btn-outline-dark btn-sm me-2">
                    📄 Baixar PDF
                </button>

                <a href="/efetividade/csv" class="btn btn-outline-secondary btn-sm">
                    📊 Exportar CSV
                </a>

            </div>

        </div>

        {{-- TABELA --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Nº</th>
                        <th>Nome</th>
                        <th>Função</th>
                        <th>Dias de Trabalho</th>
                        <th>Férias</th>
                        <th>Faltas</th>
                        <th>Observação</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($funcionarios as $key => $f)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $f->nome }}</td>
                        <td>{{ optional($f->funcao)->nome }}</td>

                        <td>
                            <input type="number" name="dias[{{ $f->id_funcionario }}]" value="22" class="form-control form-control-sm">
                        </td>

                        <td>
                            <input type="number" name="ferias[{{ $f->id_funcionario }}]" value="0" class="form-control form-control-sm">
                        </td>

                        <td>
                            <input type="number" name="faltas[{{ $f->id_funcionario }}]" value="0" class="form-control form-control-sm">
                        </td>

                        <td>
                            <input type="text" name="obs[{{ $f->id_funcionario }}]" class="form-control form-control-sm">
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </form>

</div>

@endsection