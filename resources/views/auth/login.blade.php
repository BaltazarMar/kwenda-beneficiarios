<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema FAS Lunda Sul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 13px;
            color: #6c757d;
        }

        .login-logo .logo-icon {
            width: 64px;
            height: 64px;
            background: #0d6efd;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .login-logo .logo-icon svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .btn-login {
            background: #0d6efd;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #0b5ed7;
        }

        .forgot-link {
            font-size: 13px;
            color: #0d6efd;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .form-check-label {
            font-size: 13px;
            color: #6c757d;
        }

        .alert-danger {
            border-radius: 8px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="login-card">
{{-- LOGO --}}
<div class="login-logo">
    <img src="{{ asset('images/fas.jpg') }}" alt="FAS" style="width: 100px; height: 100px; object-fit: contain; border-radius: 12px; margin-bottom: 16px;">
    <h1>Sistema FAS</h1>
    <p>Lunda Sul — Controlo de Beneficiários</p>
</div>

    {{-- ERROS --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    {{-- FORMULÁRIO --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="exemplo@email.com"
            >
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            >
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">Lembrar-me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu a password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary btn-login w-100 text-white">
            Entrar
        </button>

    </form>

</div>

</body>
</html>