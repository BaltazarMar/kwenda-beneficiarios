<!DOCTYPE html>
<html>
<head>
    <title>Painel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="bg-dark text-white p-3" style="width:250px; height:100vh;">
        <h4>Controlo</h4>
        <hr>
        <a href="{{ route('dashboard') }}" class="text-white d-block mb-2">🏠 Início</a>
        <a href="{{ url('/estagiarios') }}" class="text-white d-block mb-2">👨‍🎓 Estagiários</a>
        <a href="{{ url('/beneficiarios') }}" class="text-white d-block mb-2">👥 Lista dos beneficiários</a>
    </div>

    <!-- CONTEÚDO -->
    <div class="flex-grow-1 p-4 bg-light">

        <!-- NAVBAR -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Painel</h3>
            
        </div>

        @yield('content')

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts das views -->
@stack('scripts')

</body>
</html>