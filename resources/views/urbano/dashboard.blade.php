@extends('layouts.app')

@section('titulo', 'Dashboard Kwenda Urbano')

@section('content')

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        {{-- FILTRO ACTIVO --}}
        <div id="filtro-activo" class="d-none d-flex align-items-center gap-2">
            <span class="badge px-3 py-2" style="background:#eff6ff; color:#3b82f6; font-size:13px;" id="municipio-label"></span>
            <button class="btn btn-sm btn-outline-danger" id="btn-limpar">
                <i class="bi bi-x-lg"></i> Limpar filtro
            </button>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/urbano-importar') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-upload"></i> Importar Dados
        </a>
        <a href="{{ url('/urbano-beneficiarios') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-list-ul"></i> Ver Lista
        </a>
    </div>
</div>

{{-- CARDS --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total Beneficiários</p>
                        <h3 class="fw-bold mb-0" id="card-total" style="color:#0f172a;">{{ number_format($total, 0, ',', '.') }}</h3>
                    </div>
                    <div style="width:36px; height:36px; background:#eff6ff; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-people-fill" style="color:#3b82f6; font-size:16px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Masculino</p>
                        <h3 class="fw-bold mb-0" id="card-masculino" style="color:#3b82f6;">{{ number_format($masculino, 0, ',', '.') }}</h3>
                        <small class="text-muted" id="card-masculino-pct">{{ $total > 0 ? round(($masculino / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:36px; height:36px; background:#eff6ff; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-gender-male" style="color:#3b82f6; font-size:16px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ec4899 !important;">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Feminino</p>
                        <h3 class="fw-bold mb-0" id="card-feminino" style="color:#ec4899;">{{ number_format($feminino, 0, ',', '.') }}</h3>
                        <small class="text-muted" id="card-feminino-pct">{{ $total > 0 ? round(($feminino / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:36px; height:36px; background:#fdf2f8; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-gender-female" style="color:#ec4899; font-size:16px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #d97706 !important;">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Bairros</p>
                        <h3 class="fw-bold mb-0" id="card-bairros" style="color:#d97706;">{{ number_format($bairros, 0, ',', '.') }}</h3>
                    </div>
                    <div style="width:36px; height:36px; background:#fffbeb; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-building" style="color:#d97706; font-size:16px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRÁFICOS LINHA 1 --}}
<div class="row g-2 mb-2">

    {{-- MUNICÍPIO — PIZZA --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0 pt-2 px-3" style="background:transparent;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:26px; height:26px; background:#fffbeb; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-pie-chart-fill" style="color:#d97706; font-size:12px;"></i>
                        </div>
                        <span class="fw-bold" style="font-size:13px;">Por Município</span>
                    </div>
                    <small class="text-muted" style="font-size:10px;">Clica para filtrar</small>
                </div>
                <hr class="mt-1 mb-0">
            </div>
            <div class="card-body p-2">
                <canvas id="graficoMunicipio" style="max-height:180px;"></canvas>
            </div>
        </div>
    </div>

    {{-- CATEGORIA --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0 pt-2 px-3" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div style="width:26px; height:26px; background:#f0fdf4; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-bar-chart-fill" style="color:#16a34a; font-size:12px;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:13px;">Por Categoria</span>
                </div>
                <hr class="mt-1 mb-0">
            </div>
            <div class="card-body p-2">
                <canvas id="graficoCategoria" style="max-height:180px;"></canvas>
            </div>
        </div>
    </div>

    {{-- BAIRRO --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0 pt-2 px-3" style="background:transparent;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:26px; height:26px; background:#eff6ff; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-bar-chart-fill" style="color:#3b82f6; font-size:12px;"></i>
                        </div>
                        <span class="fw-bold" style="font-size:13px;">Por Bairro</span>
                    </div>
                    <span class="badge" id="badge-bairros" style="background:#eff6ff; color:#3b82f6; font-size:11px; font-weight:600;">
                        {{ $porBairro->count() }} bairros
                    </span>
                </div>
                <hr class="mt-1 mb-0">
            </div>
            <div class="card-body p-2">
                <div class="row g-2">
                    <div class="col-7">
                        <canvas id="graficoBairro" style="max-height:180px;"></canvas>
                    </div>
                    <div class="col-5">
                        <div id="lista-bairros" style="max-height:180px; overflow-y:auto;">
                            @foreach($porBairro as $bairro => $total)
                            <div class="d-flex justify-content-between align-items-center py-1" style="border-bottom:1px solid #f1f5f9;">
                                <span style="font-size:11px; color:#0f172a;">{{ $bairro }}</span>
                                <span class="badge" style="background:#eff6ff; color:#3b82f6; font-weight:700; font-size:10px;">{{ number_format($total, 0, ',', '.') }}</span>
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
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: { size: 10, family: 'Plus Jakarta Sans' },
                        color: '#64748b',
                        padding: 6,
                        usePointStyle: true,
                        pointStyleWidth: 6,
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
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 16 } },
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 9 } }, grid: { display: false } },
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
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 16 } },
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 9 } }, grid: { display: false } },
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
                document.getElementById('card-total').textContent         = formatNum(data.total);
                document.getElementById('card-masculino').textContent     = formatNum(data.masculino);
                document.getElementById('card-feminino').textContent      = formatNum(data.feminino);
                document.getElementById('card-bairros').textContent       = formatNum(data.bairros);
                document.getElementById('card-masculino-pct').textContent = pct(data.masculino, data.total);
                document.getElementById('card-feminino-pct').textContent  = pct(data.feminino, data.total);
                document.getElementById('badge-bairros').textContent      = formatNum(data.bairros) + ' bairros';

                graficoCategoria.data.labels = Object.keys(data.porCategoria);
                graficoCategoria.data.datasets[0].data = Object.values(data.porCategoria);
                graficoCategoria.update();

                graficoBairro.data.labels = Object.keys(data.porBairro);
                graficoBairro.data.datasets[0].data = Object.values(data.porBairro);
                graficoBairro.update();

                const lista = document.getElementById('lista-bairros');
                lista.innerHTML = '';
                Object.entries(data.porBairro).forEach(([bairro, total]) => {
                    lista.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center py-1" style="border-bottom:1px solid #f1f5f9;">
                            <span style="font-size:11px; color:#0f172a;">${bairro}</span>
                            <span class="badge" style="background:#eff6ff; color:#3b82f6; font-weight:700; font-size:10px;">${formatNum(total)}</span>
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