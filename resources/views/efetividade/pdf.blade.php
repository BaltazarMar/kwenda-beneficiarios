<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        /* Marca d’água */
        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            width: 350px;
            opacity: 0.06;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }
    </style>
</head>

<body>

<!-- MARCA D’ÁGUA -->
<img src="{{ public_path('images/fas.jpg') }}" class="watermark">

<!-- CABEÇALHO CENTRALIZADO CORRETAMENTE -->
<table style="width:100%; border:none; margin-bottom:10px;">
    <tr>
        <!-- LOGO -->
        <td style="width:20%; text-align:left; border:none;">
            <img src="{{ public_path('images/fas.jpg') }}" style="width:90px;">
        </td>

        <!-- TEXTO CENTRAL -->
        <td style="width:60%; text-align:center; border:none; font-weight:bold; line-height:1.4; font-size:14px;">
            FAS - INSTITUTO DE DESENVOLVIMENTO LOCAL<br>
            DEPARTAMENTO EXECUTIVO PROVINCIAL DA LUNDA SUL<br>
            PROGRAMA DE FORTALECIMENTO DA PROTEÇÃO SOCIAL - KWENDA
        </td>

        <!-- ESPAÇO VAZIO -->
        <td style="width:20%; border:none;"></td>
    </tr>
</table>

<!-- VISTO CHEFE -->
<div style="text-align:right; margin-bottom:10px;">
    <div style="display:inline-block; text-align:center;">
        <div>O visto do Chefe do Departamento</div>
        <div style="border-top:1px solid #000; width:200px; margin:20px auto;"></div>
        <div>________/_________/_____________</div>
    </div>
</div>

<!-- TÍTULO -->
<div class="center" style="font-weight:bold; margin:15px 0;">
    FOLHA DE EFETIVIDADE REFERENTE AO PERÍODO DE 
    {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} 
    À 
    {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
</div>

<!-- TABELA -->
<table>
    <thead>
        <tr>
            <th>Nº</th>
            <th>Nome</th>
            <th>Função</th>
            <th>Dias de Trabalho</th>
            <th>Férias</th>
            <th>Nº de Faltas</th>
            <th>Observação</th>
        </tr>
    </thead>

    <tbody>
        @foreach($funcionarios as $key => $f)
        <tr>
            <td class="center">{{ $key+1 }}</td>

            <td class="left">{{ $f->nome }}</td>

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
<div class="center" style="margin-top:15px; font-size:11px;">
    DEPARTAMENTO PROVINCIAL DO FAS - Instituto de Desenvolvimento Local, 
    na cidade de Saurimo aos 
    {{ $data->translatedFormat('d \\d\\e F \\d\\e Y') }}
</div>

<!-- ASSINATURA -->
<div class="center" style="margin-top:50px;">
    <div><strong>A Recepcionista</strong></div>

    <div style="border-top:1px solid #000; width:250px; margin:10px auto;"></div>

    <div><strong>Gladis Paulina</strong></div>
</div>

<!-- RODAPÉ -->
<div style="position:fixed; bottom:10px; left:0; right:0; text-align:center; font-size:10px;">
    Departamento Executivo do FAS - Instituto de Desenvolvimento Local, Lunda Sul,
    Rua Governador Martas Securo, junto ao Comando Provincial da PN - Saurimo
</div>

</body>
</html>