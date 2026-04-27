<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f1f3f5;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            background: #0d1b2a;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #1b263b;
            color: white;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .card-box {
            border-radius: 10px;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* 🔥 IMAGEM COMPLETA NO CARROSSEL */
        .carousel-img {
            width: 100%;
            height: 500px;
            object-fit: contain; /* NÃO CORTA */
            background: #e9ecef;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Controlo</h4>

    <a href="#">Início</a>

    <hr>

    <h5>Gestão</h5>
    <a href="{{ url('/funcionarios') }}">Funcionários</a>
    <a href="{{ url('/estagiarios') }}">Estagiários</a>
    <a href="{{ url('/adecos') }}">ADECOS</a>
    <a href="{{ url('/funcoes') }}">Funções</a>
    <a href="{{ url('/importar') }}">Importar</a>
    <a href="{{ url('/kwenda-dashboard') }}">DASHBOARD DO KWENDA RURAL</a>

    <hr>

    <h5>Relatórios</h5>
    <a href="{{ url('/efetividade') }}">Efetividade</a>
</div>

<!-- CONTEÚDO -->
<div class="content">
    <h3>Painel de Controlo</h3>
    <p>Visão geral do sistema</p>

    <!-- CARDS -->
    <div class="row mt-4">

        <div class="col-md-3">
            <div class="card-box">
                <h6>Total Funcionário</h6>
                <h3>5</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <h6>Estagiários Ativos</h6>
                <h3>2</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <h6>Pendentes</h6>
                <h3>0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <h6>Total Geral</h6>
                <h3>7</h3>
            </div>
        </div>

    </div>

    <!-- 🔥 CARROSSEL PROFISSIONAL -->
    <div class="mt-5">
        <h5 class="mb-3">Galeria da Instituição</h5>

        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">

            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="{{ asset('images/img1.jpg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img2.jpg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img3.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img4.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img13.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img6.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img7.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img8.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img9.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img10.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img11.jpeg') }}" class="carousel-img">
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/img12.jpeg') }}" class="carousel-img">
                </div>

            </div>

            <!-- Botão anterior -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <!-- Botão próximo -->
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </div>

</div>

<!-- Bootstrap JS (OBRIGATÓRIO) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>