<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema FAS — Lunda Sul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-bg: #0a0f1e;
            --sidebar-width: 260px;
            --accent: #3b82f6;
            --accent-dark: #1d4ed8;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --sidebar-text: #94a3b8;
            --sidebar-hover: rgba(59,130,246,0.12);
            --sidebar-active: rgba(59,130,246,0.2);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 100;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-logo img {
            width: 40px; height: 40px;
            object-fit: contain;
            border-radius: 8px;
        }
        .sidebar-logo-text strong {
            display: block;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        .sidebar-logo-text span {
            color: var(--sidebar-text);
            font-size: 11px;
        }
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
        }
        .nav-section { margin-bottom: 24px; }
        .nav-section-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #475569;
            padding: 0 8px;
            margin-bottom: 6px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--sidebar-text);
            padding: 9px 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .nav-link i { font-size: 16px; width: 20px; text-align: center; opacity: 0.7; }
        .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        .nav-link:hover i { opacity: 1; }
        .nav-link.active { background: var(--sidebar-active); color: var(--accent); }
        .nav-link.active i { opacity: 1; color: var(--accent); }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .btn-sair {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #f87171;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-sair:hover { background: rgba(239,68,68,0.2); color: #fca5a5; }
        .main { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar h1 { font-size: 18px; font-weight: 700; margin: 0; }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 700;
        }
        .user-info strong { display: block; font-size: 13px; font-weight: 600; }
        .user-info span { font-size: 11px; color: var(--text-muted); }
        .page-content { padding: 32px; flex: 1; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/fas.jpg') }}" alt="FAS">
        <div class="sidebar-logo-text">
            <strong>Sistema FAS</strong>
            <span>Lunda Sul</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <a href="{{ url('/painel') }}" class="nav-link {{ request()->is('painel') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Início
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Gestão</div>
            <a href="{{ url('/funcionarios') }}" class="nav-link {{ request()->is('funcionarios*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Funcionários
            </a>
            <a href="{{ url('/estagiarios') }}" class="nav-link {{ request()->is('estagiarios*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Estagiários
            </a>
            <a href="{{ url('/adecos') }}" class="nav-link {{ request()->is('adecos*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> ADECOS
            </a>
            <a href="{{ url('/funcoes') }}" class="nav-link {{ request()->is('funcoes*') ? 'active' : '' }}">
                <i class="bi bi-briefcase-fill"></i> Funções
            </a>
            <a href="{{ url('/importar') }}" class="nav-link {{ request()->is('importar*') ? 'active' : '' }}">
                <i class="bi bi-upload"></i> Importar
            </a>
            <a href="{{ url('/kwenda-dashboard') }}" class="nav-link {{ request()->is('kwenda-dashboard*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Dashboard Kwenda
            </a>
            <a href="{{ url('/beneficiarios') }}" class="nav-link">
                <i class="bi bi-bar-chart-fill"></i> Lista dos beneficiarios
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Relatórios</div>
            <a href="{{ url('/efetividade') }}" class="nav-link {{ request()->is('efetividade*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Efetividade
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ url('/sair') }}" class="btn-sair">
            <i class="bi bi-box-arrow-left"></i> Terminar Sessão
        </a>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h1>@yield('titulo', 'Sistema FAS')</h1>
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <strong>{{ auth()->user()->name ?? 'Utilizador' }}</strong>
                <span>{{ auth()->user()->getRoleNames()->first() ?? '' }}</span>
            </div>
        </div>
    </div>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>