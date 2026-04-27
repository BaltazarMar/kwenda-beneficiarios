<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    
<p style="text-align:right;">
    Data de emissão: {{ date('d/m/Y') }}
</p>
    <style>
        body {
            font-family: Arial;
            position: relative;
        }

        /* Marca d’água */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 400px;
            opacity: 0.08;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        /* Logo topo */
        .logo {
            width: 90px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eee;
        }
        
    </style>
</head>

<body>

<!-- FUNDO (marca d’água) -->
<img src="{{ public_path('images/fas.jpg') }}" class="watermark">

<!-- CABEÇALHO -->
<div class="header">
    <img src="{{ public_path('images/fas.jpg') }}" class="logo">

    <div>
        <h2 style="text-align:center;">Lista de Estagiários</h2>
       
    </div>
</div>

<!-- TABELA -->
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Sexo</th>
            <th>BI</th>
            <th>Data Nascimento</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach($estagiarios as $e)
        <tr>
            <td>{{ $e->nome }}</td>
            <td>{{ $e->sexo }}</td>
            <td>{{ $e->bi }}</td>
            <td>{{ $e->data_nascimento }}</td>
            <td>{{ $e->estado }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br><br>

<div style="text-align:center; margin-top:60px;">
    <div style="display:inline-block;">
        <div style="font-weight:bold;">Baltazar Mariano</div><br>
        <div style="border-top:1px solid #000; width:250px; margin-top:5px;"></div>
        <div><strong>Assistente de dados</strong></div>
    </div>
<div style="position:absolute; top:120px; right:40px; text-align:center; width:300px;">

    <div style="font-weight:bold;">Visto do chefe do departamento</div>

    <div style="border-top:1px solid #000; margin:10px auto; width:250px;"></div>

    <div><strong>Eng. Nome do Diretor</strong></div>

</div>
</div>

</body>
</html>