@extends('layouts.app')

@section('titulo', 'Dashboard Kwenda Urbano')

@section('content')

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard Kwenda Urbano</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Estatísticas dos beneficiários urbanos — Saurimo</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        {{-- FILTRO ACTIVO --}}
        <div id="filtro-activo" class="d-none d-flex align-items-center gap-2">
            <span class="badge px-3 py-2" style="background:#eff6ff; color:#3b82f6; font-size:13px;" id="municipio-label"></span>
            <button class="btn btn-sm btn-outline-danger" id="btn-limpar">
                <i class="bi bi-x-lg"></i> Limpar filtro
            </button>
        </div>
        <a href="{{ url('/urbano-importar') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-upload"></i> Importar Dados
        </a>
        <a href="{{ url('/urbano-beneficiarios') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-list-ul"></i> Ver Lista
        </a>
    </div>
</div>

{{-- CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total Beneficiários</p>
                        <h2 class="fw-bold mb-0" id="card-total" style="color:#0f172a;">{{ number_format($total, 0, ',', '.') }}</h2>
                    </div>
                    <div style="width:40px; height:40px; background:#eff6ff; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-people-fill" style="color:#3b82f6; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Masculino</p>
                        <h2 class="fw-bold mb-0" id="card-masculino" style="color:#3b82f6;">{{ number_format($masculino, 0, ',', '.') }}</h2>
                        <small class="text-muted" id="card-masculino-pct">{{ $total > 0 ? round(($masculino / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:40px; height:40px; background:#eff6ff; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-gender-male" style="color:#3b82f6; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ec4899 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Feminino</p>
                        <h2 class="fw-bold mb-0" id="card-feminino" style="color:#ec4899;">{{ number_format($feminino, 0, ',', '.') }}</h2>
                        <small class="text-muted" id="card-feminino-pct">{{ $total > 0 ? round(($feminino / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:40px; height:40px; background:#fdf2f8; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-gender-female" style="color:#ec4899; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #d97706 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Bairros</p>
                        <h2 class="fw-bold mb-0" id="card-bairros" style="color:#d97706;">{{ number_format($bairros, 0, ',', '.') }}</h2>
                    </div>
                    <div style="width:40px; height:40px; background:#fffbeb; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-building" style="color:#d97706; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRÁFICOS LINHA 1 --}}
<div class="row g-3 mb-3">

    {{-- MUNICÍPIO — PIZZA --}}
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px; height:32px; background:#fffbeb; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-pie-chart-fill" style="color:#d97706;"></i>
                        </div>
                        <span class="fw-bold" style="font-size:14px;">Distribuição por Município</span>
                    </div>
                    <small class="text-muted" style="font-size:11px;">Clica numa fatia para filtrar</small>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <canvas id="graficoMunicipio"></canvas>
            </div>
        </div>
    </div>

    {{-- CATEGORIA --}}
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#f0fdf4; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-bar-chart-fill" style="color:#16a34a;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Distribuição por Categoria</span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <canvas id="graficoCategoria"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- GRÁFICO BAIRRO --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-bar-chart-fill" style="color:#3b82f6;"></i>
                        </div>
                        <span class="fw-bold" style="font-size:14px;">Distribuição por Bairro</span>
                    </div>
                    <span class="badge" id="badge-bairros" style="background:#eff6ff; color:#3b82f6; font-size:12px; font-weight:600;">
                        {{ $porBairro->count() }} bairros
                    </span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <div class="row g-3">
                    <div class="col-md-8">
                        <canvas id="graficoBairro" style="max-height:280px;"></canvas>
                    </div>
                    <div class="col-md-4">
                        <div id="lista-bairros" style="max-height:280px; overflow-y:auto;">
                            @foreach($porBairro as $bairro => $total)
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
                                <span style="font-size:13px; color:#0f172a;">{{ $bairro }}</span>
                                <span class="badge" style="background:#eff6ff; color:#3b82f6; font-weight:700;">{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let municipioActivo = null;

    const pluginNumeros = {
        id: 'pluginNumeros',
        afterDatasetsDraw(chart) {
            const { ctx, data } = chart;
            ctx.save();
            data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((bar, index) => {
                    const value = dataset.data[index];
                    ctx.font = 'bold 10px Arial';
                    ctx.fillStyle = '#64748b';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillText(Number(value).toLocaleString('pt-PT'), bar.x, bar.y - 4);
                });
            });
            ctx.restore();
        }
    };

    // ===== GRÁFICO MUNICÍPIO — DOUGHNUT =====
    const graficoMunicipio = new Chart(document.getElementById('graficoMunicipio').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($porMunicipio->keys()) !!},
            datasets: [{
                data: {!! json_encode($porMunicipio->values()) !!},
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#ec4899'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.6,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        font: { size: 12, family: 'Plus Jakarta Sans' },
                        color: '#64748b',
                        padding: 12,
                        usePointStyle: true,
                        pointStyleWidth: 8,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ': ' + Number(ctx.raw).toLocaleString('pt-PT') + ' benef.'
                    }
                }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const municipio = graficoMunicipio.data.labels[elements[0].index];
                    if (municipioActivo === municipio) {
                        municipioActivo = null;
                        document.getElementById('filtro-activo').classList.add('d-none');
                    } else {
                        municipioActivo = municipio;
                        document.getElementById('municipio-label').textContent = '📍 ' + municipio;
                        document.getElementById('filtro-activo').classList.remove('d-none');
                    }
                    carregarTudo();
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    // ===== GRÁFICO CATEGORIA =====
    const graficoCategoria = new Chart(document.getElementById('graficoCategoria').getContext('2d'), {
        type: 'bar',
        plugins: [pluginNumeros],
        data: {
            labels: {!! json_encode($porCategoria->keys()) !!},
            datasets: [{
                data: {!! json_encode($porCategoria->values()) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.9)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 20 } },
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 10 } }, grid: { display: false } },
                y: { display: false, beginAtZero: true }
            }
        }
    });

    // ===== GRÁFICO BAIRRO =====
    const graficoBairro = new Chart(document.getElementById('graficoBairro').getContext('2d'), {
        type: 'bar',
        plugins: [pluginNumeros],
        data: {
            labels: {!! json_encode($porBairro->keys()) !!},
            datasets: [{
                data: {!! json_encode($porBairro->values()) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.9)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 20 } },
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 10 } }, grid: { display: false } },
                y: { display: false, beginAtZero: true }
            }
        }
    });

    // ===== CARREGAR TUDO =====
    function carregarTudo() {
        let url = '/urbano-filtros';
        if (municipioActivo) url += `?municipio=${encodeURIComponent(municipioActivo)}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                // Actualizar cards
                document.getElementById('card-total').textContent          = formatNum(data.total);
                document.getElementById('card-masculino').textContent      = formatNum(data.masculino);
                document.getElementById('card-feminino').textContent       = formatNum(data.feminino);
                document.getElementById('card-bairros').textContent        = formatNum(data.bairros);
                document.getElementById('card-masculino-pct').textContent  = pct(data.masculino, data.total);
                document.getElementById('card-feminino-pct').textContent   = pct(data.feminino, data.total);
                document.getElementById('badge-bairros').textContent       = formatNum(data.bairros) + ' bairros';

                // Actualizar gráfico categoria
                graficoCategoria.data.labels = Object.keys(data.porCategoria);
                graficoCategoria.data.datasets[0].data = Object.values(data.porCategoria);
                graficoCategoria.update();

                // Actualizar gráfico bairro
                graficoBairro.data.labels = Object.keys(data.porBairro);
                graficoBairro.data.datasets[0].data = Object.values(data.porBairro);
                graficoBairro.update();

                // Actualizar lista de bairros
                const lista = document.getElementById('lista-bairros');
                lista.innerHTML = '';
                Object.entries(data.porBairro).forEach(([bairro, total]) => {
                    lista.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
                            <span style="font-size:13px; color:#0f172a;">${bairro}</span>
                            <span class="badge" style="background:#eff6ff; color:#3b82f6; font-weight:700;">${formatNum(total)}</span>
                        </div>`;
                });
            });
    }

    // ===== LIMPAR FILTRO =====
    document.getElementById('btn-limpar').addEventListener('click', () => {
        municipioActivo = null;
        document.getElementById('filtro-activo').classList.add('d-none');
        carregarTudo();
    });

    function formatNum(n) { return Number(n).toLocaleString('pt-PT'); }
    function pct(valor, total) {
        if (!total) return '0%';
        return (valor / total * 100).toFixed(1) + '%';
    }
</script>
@endpush