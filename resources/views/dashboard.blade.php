<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema FAS — Painel de Controlo</title>

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
            --accent-light: #eff6ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
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

        /* ===== SIDEBAR ===== */
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

        .sidebar-logo img { width: 40px; height: 40px; object-fit: contain; border-radius: 8px; }
        .sidebar-logo-text { line-height: 1.2; }
        .sidebar-logo-text strong { display: block; color: #fff; font-size: 15px; font-weight: 700; }
        .sidebar-logo-text span { color: var(--sidebar-text); font-size: 11px; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 16px 12px; }

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

        /* ===== BOTÃO KWENDA INFO ===== */
        .kwenda-info-wrap {
            position: relative;
            width: 100%;
            margin-bottom: 2px;
        }

        .kwenda-info-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 10px;
            border-radius: 8px;
            background: rgba(59,130,246,0.08);
            border: 1px solid rgba(59,130,246,0.18);
            color: #93c5fd;
            font-size: 13.5px;
            font-weight: 500;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.15s;
            text-align: left;
        }

        .kwenda-info-btn i { font-size: 16px; width: 20px; text-align: center; }

        .kwenda-info-btn .chevron {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.2s;
            opacity: 0.6;
        }

        .kwenda-info-wrap:hover .kwenda-info-btn {
            background: rgba(59,130,246,0.15);
            color: #bfdbfe;
        }

        .kwenda-info-wrap:hover .chevron {
            transform: rotate(180deg);
        }

        /* Dropdown — vertical: Rural em cima, Urbano em baixo */
        .kwenda-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 999;
            background: #0f172a;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 10px;
            gap: 8px;
            width: 220px;
            flex-direction: column;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }

        .kwenda-info-wrap:hover .kwenda-dropdown {
            display: flex;
        }

        .kwenda-col {
            background: rgba(255,255,255,0.04);
            border-radius: 8px;
            padding: 10px 12px;
        }

        .kwenda-col-title {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .kwenda-col-title.rural  { color: #86efac; }
        .kwenda-col-title.urbano { color: #93c5fd; }

        .kwenda-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 12.5px;
            color: #94a3b8;
            text-decoration: none;
            transition: background 0.12s, color 0.12s;
        }

        .kwenda-item:hover { background: rgba(255,255,255,0.07); color: #e2e8f0; }
        .kwenda-item i { font-size: 14px; width: 16px; text-align: center; }

        /* ===== MAIN CONTENT ===== */
        .main { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }

        /* ===== TOPBAR ===== */
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

        .topbar-title h1 { font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0; }
        .topbar-title p  { font-size: 12px; color: var(--text-muted); margin: 0; }
        .topbar-user     { display: flex; align-items: center; gap: 10px; }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 14px; font-weight: 700;
        }

        .user-info strong { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .user-info span   { font-size: 11px; color: var(--text-muted); }

        /* ===== PAGE CONTENT ===== */
        .page-content { padding: 32px; flex: 1; }

        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 0 16px 0 80px;
            opacity: 0.08;
        }

        .stat-card.blue::before   { background: var(--accent); }
        .stat-card.green::before  { background: var(--success); }
        .stat-card.orange::before { background: var(--warning); }
        .stat-card.purple::before { background: #8b5cf6; }

        .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; }
        .stat-card.blue .stat-icon   { background: #eff6ff; color: var(--accent); }
        .stat-card.green .stat-icon  { background: #f0fdf4; color: var(--success); }
        .stat-card.orange .stat-icon { background: #fffbeb; color: var(--warning); }
        .stat-card.purple .stat-icon { background: #f5f3ff; color: #8b5cf6; }

        .stat-value { font-size: 32px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 6px; }
        .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 500; }

        /* ===== QUICK ACCESS ===== */
        .section-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; }

        .quick-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }

        .quick-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }

        .quick-card:hover { border-color: var(--accent); box-shadow: 0 4px 16px rgba(59,130,246,0.1); transform: translateY(-1px); }

        .quick-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }

        .quick-card .quick-text strong { display: block; font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px; }
        .quick-card .quick-text span  { font-size: 12px; color: var(--text-muted); }

        /* ===== GALLERY ===== */
        .gallery-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }

        .gallery-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .gallery-header h5 { font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }

        .carousel-img { width: 100%; height: 500px; object-fit: contain; background: #f8fafc; }

        .carousel-indicators [data-bs-target] { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.5); }
        .carousel-indicators .active { background: white; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
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
            <a href="{{ url('/painel') }}" class="nav-link active">
                <i class="bi bi-house-door-fill"></i> Início
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Gestão</div>
            <a href="{{ url('/funcionarios') }}" class="nav-link">
                <i class="bi bi-people-fill"></i> Funcionários
            </a>
            <a href="{{ url('/estagiarios') }}" class="nav-link">
                <i class="bi bi-person-badge-fill"></i> Estagiários
            </a>
            <a href="{{ url('/adecos') }}" class="nav-link">
                <i class="bi bi-building"></i> ADECOS
            </a>
            <a href="{{ url('/funcoes') }}" class="nav-link">
                <i class="bi bi-briefcase-fill"></i> Funções
            </a>
            <a href="{{ url('/importar') }}" class="nav-link">
                <i class="bi bi-upload"></i> Importar Rural
            </a>
            <a href="{{ url('/urbano-importar') }}" class="nav-link">
                <i class="bi bi-upload"></i> Importar Urbano
            </a>
            <a href="{{ url('/kobo/sync') }}" class="nav-link">
                <i class="bi bi-cloud-arrow-down-fill"></i> KoboToolbox
            </a>
        </div>

        {{-- ===== BOTÃO: INFORMAÇÃO SOBRE O KWENDA ===== --}}
        <div class="nav-section">
            <div class="nav-section-title">Kwenda</div>

            <div class="kwenda-info-wrap">
                <button class="kwenda-info-btn">
                    <i class="bi bi-info-circle-fill"></i>
                    Informação sobre o Kwenda
                    <i class="bi bi-chevron-down chevron"></i>
                </button>

                <div class="kwenda-dropdown">
                    {{-- Kwenda Rural --}}
                    <div class="kwenda-col">
                        <div class="kwenda-col-title rural">
                            <i class="bi bi-tree-fill"></i> Kwenda Rural
                        </div>
                        <a href="{{ url('/kwenda-dashboard') }}" class="kwenda-item">
                            <i class="bi bi-bar-chart-fill"></i> Dashboard
                        </a>
                        <a href="{{ url('/beneficiarios') }}" class="kwenda-item">
                            <i class="bi bi-list-ul"></i> Lista
                        </a>
                        <a href="{{ url('/importar') }}" class="kwenda-item">
                            <i class="bi bi-upload"></i> Importação
                        </a>
                    </div>

                    {{-- Kwenda Urbano --}}
                    <div class="kwenda-col">
                        <div class="kwenda-col-title urbano">
                            <i class="bi bi-buildings-fill"></i> Kwenda Urbano
                        </div>
                        <a href="{{ url('/urbano-dashboard') }}" class="kwenda-item">
                            <i class="bi bi-bar-chart-fill"></i> Dashboard
                        </a>
                        <a href="{{ url('/urbano-beneficiarios') }}" class="kwenda-item">
                            <i class="bi bi-list-ul"></i> Lista
                        </a>
                        <a href="{{ url('/urbano-importar') }}" class="kwenda-item">
                            <i class="bi bi-upload"></i> Importação
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- ===== FIM BOTÃO KWENDA ===== --}}

        <div class="nav-section">
            <div class="nav-section-title">Relatórios</div>
            <a href="{{ url('/efetividade') }}" class="nav-link">
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

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-title">
            <h1>Painel de Controlo</h1>
            <p>Bem-vindo ao Sistema de Controlo FAS Lunda Sul</p>
        </div>
        <div class="topbar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <strong>{{ auth()->user()->name ?? 'Utilizador' }}</strong>
                <span>{{ auth()->user()->getRoleNames()->first() ?? 'Sistema' }}</span>
            </div>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-value">{{ \App\Models\Funcionario::count() ?? 0 }}</div>
                <div class="stat-label">Total de Funcionários</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="bi bi-person-badge-fill"></i></div>
                <div class="stat-value">{{ \App\Models\Estagiario::where('estado', 'ativo')->count() ?? 0 }}</div>
                <div class="stat-label">Estagiários Activos</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
                <div class="stat-value">{{ \App\Models\Estagiario::where('estado', 'pendente')->count() ?? 0 }}</div>
                <div class="stat-label">Pendentes</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
                <div class="stat-value">{{ \App\Models\Estagiario::count() + \App\Models\Funcionario::count() ?? 0 }}</div>
                <div class="stat-label">Total Geral</div>
            </div>
        </div>

        <!-- ACESSO RÁPIDO -->
        <p class="section-title">Acesso Rápido</p>
        <div class="quick-grid">
            <a href="{{ url('/kwenda-dashboard') }}" class="quick-card">
                <div class="quick-icon" style="background:#eff6ff; color:#3b82f6;">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div class="quick-text">
                    <strong>Dashboard Kwenda</strong>
                    <span>Ver estatísticas dos beneficiários</span>
                </div>
            </a>
            <a href="{{ url('/beneficiarios') }}" class="quick-card">
                <div class="quick-icon" style="background:#f0fdf4; color:#10b981;">
                    <i class="bi bi-people"></i>
                </div>
                <div class="quick-text">
                    <strong>Beneficiários</strong>
                    <span>Gerir lista de beneficiários</span>
                </div>
            </a>
            <a href="{{ url('/importar') }}" class="quick-card">
                <div class="quick-icon" style="background:#fffbeb; color:#f59e0b;">
                    <i class="bi bi-upload"></i>
                </div>
                <div class="quick-text">
                    <strong>Importar Dados</strong>
                    <span>Importar ficheiro Excel</span>
                </div>
            </a>
            <a href="{{ url('/funcionarios') }}" class="quick-card">
                <div class="quick-icon" style="background:#f5f3ff; color:#8b5cf6;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="quick-text">
                    <strong>Funcionários</strong>
                    <span>Gerir equipa</span>
                </div>
            </a>
            <a href="{{ url('/efetividade') }}" class="quick-card">
                <div class="quick-icon" style="background:#fff1f2; color:#ef4444;">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <div class="quick-text">
                    <strong>Efetividade</strong>
                    <span>Ver relatório de efetividade</span>
                </div>
            </a>
            <a href="{{ url('/estagiarios') }}" class="quick-card">
                <div class="quick-icon" style="background:#ecfdf5; color:#059669;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="quick-text">
                    <strong>Estagiários</strong>
                    <span>Gerir estagiários</span>
                </div>
            </a>
        </div>

        <!-- GALERIA -->
        <p class="section-title">Galeria da Instituição</p>
        <div class="gallery-card">
            <div class="gallery-header">
                <h5><i class="bi bi-images" style="color:#3b82f6;"></i> Fotos da Instituição</h5>
                <span style="font-size:12px; color:#64748b;">Actualização automática a cada 3 segundos</span>
            </div>

            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-indicators">
                    @for($i = 0; $i < 12; $i++)
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="{{ $i }}" {{ $i == 0 ? 'class=active' : '' }}></button>
                    @endfor
                </div>
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
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>