<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
            color: #1a1a1a;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            width: 350px;
            opacity: 0.05;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        /* TABELA PRINCIPAL */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #1e3a5f;
            color: #ffffff;
            padding: 8px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #1e3a5f;
        }

        td {
            padding: 7px 6px;
            border: 1px solid #d1d5db;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f0f4f8;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .center { text-align: center; }
        .left   { text-align: left; }

        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 12px;
        }

        .header-table td { border: none; padding: 0; }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 16px 0 10px;
            text-transform: uppercase;
            color: #1e3a5f;
        }

        .linha-divisora {
            border: none;
            border-top: 2px solid #1e3a5f;
            margin: 8px 0 12px;
        }
    </style>
</head>

<body>

<!-- MARCA D'ÁGUA -->
<img src="{{ public_path('images/fas.jpg') }}" class="watermark">

<!-- CABEÇALHO -->
<table class="header-table">
    <tr>
        <td style="width:15%; text-align:left;">
            <img src="{{ public_path('images/fas.jpg') }}" style="width:80px;">
        </td>
        <td style="width:70%; text-align:center; font-weight:bold; line-height:1.6; font-size:13px; color:#1e3a5f;">
            FAS - INSTITUTO DE DESENVOLVIMENTO LOCAL<br>
            DEPARTAMENTO EXECUTIVO PROVINCIAL DA LUNDA SUL<br>
            PROGRAMA DE FORTALECIMENTO DA PROTEÇÃO SOCIAL - KWENDA
        </td>
        <td style="width:15%; text-align:right; font-size:10px; color:#555; vertical-align:top; padding-top:4px;">
            <div>O visto do Chefe do Departamento</div>
            <div style="border-top:1px solid #000; width:160px; margin:20px 0 4px auto;"></div>
            <div>______/______/______</div>
        </td>
    </tr>
</table>

<hr class="linha-divisora">

<!-- TÍTULO -->
<div class="titulo">
    Folha de Efetividade — Período de
    {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }}
    a
    {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
</div>

<!-- TABELA -->
<table>
    <thead>
        <tr>
            <th style="width:4%;">Nº</th>
            <th style="width:25%;">Nome</th>
            <th style="width:22%;">Função</th>
            <th style="width:14%;">Dias de Trabalho</th>
            <th style="width:10%;">Férias</th>
            <th style="width:10%;">Nº de Faltas</th>
            <th style="width:15%;">Observação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($funcionarios as $key => $f)
        <tr>
            <td class="center">{{ $key + 1 }}</td>
            <td class="left" style="font-weight:600;">{{ $f->nome }}</td>
            <td class="left">{{ optional($f->funcao)->nome }}</td>
            <td class="center">{{ $dias[$f->id_funcionario] ?? 22 }}</td>
            <td class="center">{{ $ferias[$f->id_funcionario] ?? 0 }}</td>
            <td class="center">{{ $faltas[$f->id_funcionario] ?? 0 }}</td>
            <td class="left">{{ $obs[$f->id_funcionario] ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- TEXTO FINAL -->
<div class="center" style="margin-top:16px; font-size:11px; color:#444;">
    DEPARTAMENTO PROVINCIAL DO FAS - Instituto de Desenvolvimento Local,
    na cidade de Saurimo aos {{ $data->translatedFormat('d \\d\\e F \\d\\e Y') }}
</div>

<!-- ASSINATURA -->
<div class="center" style="margin-top:48px;">
    <strong>A Recepcionista</strong>
    <div style="border-top:1px solid #000; width:220px; margin:12px auto 6px;"></div>
    <strong>Gladis Paulina</strong>
</div>

<!-- RODAPÉ -->
<div style="position:fixed; bottom:10px; left:0; right:0; text-align:center; font-size:9px; color:#888;">
    Departamento Executivo do FAS - Instituto de Desenvolvimento Local, Lunda Sul,
    Rua Governador Martas Securo, junto ao Comando Provincial da PN - Saurimo
</div>

</body>
</html>