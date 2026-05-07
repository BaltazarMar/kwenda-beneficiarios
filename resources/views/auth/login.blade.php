<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema FAS Lunda Sul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #0a0f1e;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            top: -100px; left: -100px;
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            border-radius: 50%;
        }
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 24px;
        }
        .login-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 48px 40px;
            backdrop-filter: blur(20px);
            box-shadow: 0 32px 64px rgba(0,0,0,0.4);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 36px;
        }
        .login-logo img {
            width: 72px; height: 72px;
            object-fit: contain;
            border-radius: 16px;
            margin-bottom: 16px;
            filter: drop-shadow(0 4px 16px rgba(59,130,246,0.3));
        }
        .login-logo h1 {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .login-logo p {
            font-size: 13px;
            color: #64748b;
        }
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .form-control {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            color: #ffffff;
            transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .form-control::placeholder { color: #475569; }
        .form-control:focus {
            background: rgba(255,255,255,0.08);
            border-color: rgba(59,130,246,0.6);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
            color: #ffffff;
            outline: none;
        }
        .form-check-input {
            background-color: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.2);
        }
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .form-check-label {
            font-size: 13px;
            color: #64748b;
        }
        .forgot-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { color: #60a5fa; text-decoration: underline; }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.3px;
            transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-shadow: 0 4px 16px rgba(59,130,246,0.3);
            cursor: pointer;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.4);
        }
        .btn-login:active { transform: translateY(0); }
        .alert-danger {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 13px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: rgba(22,163,74,0.1);
            border: 1px solid rgba(22,163,74,0.2);
            border-radius: 10px;
            color: #86efac;
            font-size: 13px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #334155;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
        }
        .p1 { width:4px; height:4px; background:#3b82f6; top:20%; left:15%; }
        .p2 { width:6px; height:6px; background:#6366f1; top:60%; left:8%; }
        .p3 { width:3px; height:3px; background:#3b82f6; top:40%; right:12%; }
        .p4 { width:5px; height:5px; background:#8b5cf6; top:75%; right:20%; }
        .p5 { width:4px; height:4px; background:#3b82f6; top:10%; right:30%; }
    </style>
</head>
<body>

<div class="particle p1"></div>
<div class="particle p2"></div>
<div class="particle p3"></div>
<div class="particle p4"></div>
<div class="particle p5"></div>

<div class="login-wrapper">
    <div class="login-card">

        <div class="login-logo">
            <img src="{{ asset('images/fas.jpg') }}" alt="FAS">
            <h1>Sistema FAS</h1>
            <p>Lunda Sul — Controlo de Beneficiários</p>
        </div>

        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="exemplo@fas.ao"
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control"
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

            <button type="submit" class="btn-login">
                Entrar no Sistema
            </button>
        </form>

    </div>

    <div class="footer-text">
        FAS — Instituto de Desenvolvimento Local · Lunda Sul · Angola
    </div>
</div>

</body>
</html>