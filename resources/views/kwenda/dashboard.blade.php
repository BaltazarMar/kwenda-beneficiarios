@extends('layouts.app')

@section('titulo', 'Dashboard Kwenda Rural')

@section('content')

{{-- TOPO --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard Kwenda Rural</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Estatísticas dos beneficiários da Lunda Sul</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">

        {{-- Filtro de Ano --}}
        <select id="filtro-ano" class="form-select form-select-sm w-auto">
            <option value="">Todos os anos</option>
        </select>

        {{-- Badge município activo --}}
        <div id="filtro-activo" class="d-none d-flex align-items-center gap-2">
            <span class="badge px-3 py-2" style="background:#eff6ff; color:#3b82f6; font-size:13px;" id="municipio-label"></span>
            <button class="btn btn-sm btn-outline-danger" id="btn-limpar">
                <i class="bi bi-x-lg"></i> Limpar filtro
            </button>
        </div>

    </div>
</div>

{{-- CARDS LINHA 1 --}}
<div class="row g-3 mb-3">

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total de Beneficiários</p>
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
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #16a34a !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pagos</p>
                        <h2 class="fw-bold mb-0" id="card-pagos" style="color:#0f172a;">{{ number_format($pagos, 0, ',', '.') }}</h2>
                        <small class="text-muted" id="card-pagos-pct">{{ $total > 0 ? round(($pagos / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:40px; height:40px; background:#f0fdf4; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-check-circle-fill" style="color:#16a34a; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Não Pagos</p>
                        <h2 class="fw-bold mb-0" id="card-naopagos" style="color:#0f172a;">{{ number_format($naoPagos, 0, ',', '.') }}</h2>
                        <small class="text-muted" id="card-naopagos-pct">{{ $total > 0 ? round(($naoPagos / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:40px; height:40px; background:#fff1f2; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-x-circle-fill" style="color:#dc2626; font-size:18px;"></i>
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
                        <p class="text-muted mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Inelegíveis</p>
                        <h2 class="fw-bold mb-0" id="card-nuncapagos" style="color:#0f172a;">{{ number_format($nuncaPagos, 0, ',', '.') }}</h2>
                        <small class="text-muted" id="card-nuncapagos-pct">{{ $total > 0 ? round(($nuncaPagos / $total) * 100, 1) : 0 }}%</small>
                    </div>
                    <div style="width:40px; height:40px; background:#fffbeb; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-dash-circle-fill" style="color:#d97706; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CARDS LINHA 2 --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
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
        <div class="card border-0 shadow-sm h-100">
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
        <div class="card border-0 shadow-sm h-100">
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

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background:#0f172a;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#94a3b8;">Total Arrecadado</p>
                        <h2 class="fw-bold mb-0" id="card-valor" style="color:#ffffff; font-size:20px;">{{ number_format($valorTotal, 0, ',', '.') }} Kz</h2>
                    </div>
                    <div style="width:40px; height:40px; background:rgba(255,255,255,0.1); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-cash-stack" style="color:#ffffff; font-size:18px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- GRÁFICOS --}}
<div class="row g-3">

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-bar-chart-fill" style="color:#3b82f6;"></i>
                        </div>
                        <span class="fw-bold" style="font-size:14px;">Distribuição por Município</span>
                    </div>
                    <small class="text-muted" style="font-size:11px;">Clica numa barra para filtrar</small>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <canvas id="graficoMunicipio"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:32px; height:32px; background:#f0fdf4; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-graph-up" style="color:#16a34a;"></i>
                    </div>
                    <span class="fw-bold" style="font-size:14px;">Recorrências pagas <span id="label-municipio-rec" class="text-primary"></span></span>
                </div>
                <hr class="mt-0">
            </div>
            <div class="card-body pt-0">
                <canvas id="graficoRecorrencias"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let municipioActivo = null;
    let anoActivo       = null;

    const dadosMunicipios = {
        labels: {!! json_encode($porMunicipio->keys()) !!},
        values: {!! json_encode($porMunicipio->values()) !!}
    };

    const pluginNumeros = {
        id: 'pluginNumeros',
        afterDatasetsDraw(chart) {
            const { ctx, data } = chart;
            ctx.save();
            data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((bar, index) => {
                    const value = dataset.data[index];
                    ctx.font         = 'bold 11px Arial';
                    ctx.fillStyle    = '#64748b';
                    ctx.textAlign    = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillText(Number(value).toLocaleString('pt-PT'), bar.x, bar.y - 4);
                });
            });
            ctx.restore();
        }
    };

    const ctxMunicipio = document.getElementById('graficoMunicipio').getContext('2d');
    const graficoMunicipio = new Chart(ctxMunicipio, {
        type: 'bar',
        plugins: [pluginNumeros],
        data: {
            labels: dadosMunicipios.labels,
            datasets: [{
                label: 'Beneficiários',
                data: dadosMunicipios.values,
                backgroundColor: dadosMunicipios.labels.map(() => 'rgba(59, 130, 246, 0.7)'),
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 20 } },
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 11 } }, grid: { display: false } },
                y: { display: false, beginAtZero: true }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const municipio = graficoMunicipio.data.labels[elements[0].index];
                    filtrarPorMunicipio(municipio);
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    const ctxRec = document.getElementById('graficoRecorrencias').getContext('2d');
    const graficoRecorrencias = new Chart(ctxRec, {
        type: 'bar',
        plugins: [pluginNumeros],
        data: {
            labels: ['Rec 1', 'Rec 2', 'Rec 3', 'Rec 4', 'Rec 5', 'Rec 6'],
            datasets: [{
                label: 'Beneficiários pagos',
                data: [0, 0, 0, 0, 0, 0],
                backgroundColor:'rgba(37, 99, 235, 0.9)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 20 } },
            scales: {
                x: { ticks: { font: { size: 12 } }, grid: { display: false } },
                y: { display: false, beginAtZero: true }
            }
        }
    });

    function carregarAnos() {
        fetch('/dashboard-recorrencias')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('filtro-ano');
                select.innerHTML = '<option value="">Todos os anos</option>';
                data.anos.forEach(ano => {
                    const opt = document.createElement('option');
                    opt.value = ano;
                    opt.textContent = ano;
                    select.appendChild(opt);
                });
            });
    }

    function carregarRecorrencias() {
        let url = '/dashboard-recorrencias?';
        if (municipioActivo) url += `municipio=${encodeURIComponent(municipioActivo)}&`;
        if (anoActivo)       url += `ano=${anoActivo}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                graficoRecorrencias.data.datasets[0].data = Object.values(data.recorrencias);
                graficoRecorrencias.update();
                document.getElementById('label-municipio-rec').textContent =
                    municipioActivo ? '— ' + municipioActivo : '';
            });
    }

    function carregarTudo() {
        let url = '/dashboard-filtros?';
        if (municipioActivo) url += `municipio=${encodeURIComponent(municipioActivo)}&`;
        if (anoActivo)       url += `ano=${anoActivo}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                actualizarDashboard(data);
                const labels = Object.keys(data.porMunicipio);
                const values = Object.values(data.porMunicipio);
                graficoMunicipio.data.labels = labels;
                graficoMunicipio.data.datasets[0].data = values;
                graficoMunicipio.data.datasets[0].backgroundColor = labels.map(m =>
                    m === municipioActivo ? 'rgba(245, 158, 11, 0.9)' : 'rgba(59, 130, 246, 0.7)'
                );
                graficoMunicipio.update();
            });

        carregarRecorrencias();
    }

    document.getElementById('filtro-ano').addEventListener('change', function() {
        anoActivo = this.value || null;
        carregarTudo();
    });

    function filtrarPorMunicipio(municipio) {
        municipioActivo = municipio;
        document.getElementById('filtro-activo').classList.remove('d-none');
        document.getElementById('municipio-label').textContent = '📍 ' + municipio;
        graficoMunicipio.data.datasets[0].backgroundColor = graficoMunicipio.data.labels.map(m =>
            m === municipio ? 'rgba(245, 158, 11, 0.9)' : 'rgba(59, 130, 246, 0.3)'
        );
        graficoMunicipio.update();
        carregarRecorrencias();
        let url = `/dashboard-filtros?municipio=${encodeURIComponent(municipio)}`;
        if (anoActivo) url += `&ano=${anoActivo}`;
        fetch(url).then(res => res.json()).then(data => actualizarDashboard(data));
    }

    document.getElementById('btn-limpar').addEventListener('click', () => {
        municipioActivo = null;
        document.getElementById('filtro-activo').classList.add('d-none');
        carregarTudo();
    });

    function actualizarDashboard(data) {
        const total = data.total;
        document.getElementById('card-total').textContent      = formatNum(total);
        document.getElementById('card-pagos').textContent      = formatNum(data.pagos);
        document.getElementById('card-naopagos').textContent   = formatNum(data.naoPagos);
        document.getElementById('card-nuncapagos').textContent = formatNum(data.nuncaPagos);
        document.getElementById('card-masculino').textContent  = formatNum(data.masculino);
        document.getElementById('card-feminino').textContent   = formatNum(data.feminino);
        document.getElementById('card-bairros').textContent    = formatNum(data.bairros);
        document.getElementById('card-valor').textContent      = formatNum(data.valorTotal) + ' Kz';
        document.getElementById('card-pagos-pct').textContent      = pct(data.pagos, total);
        document.getElementById('card-naopagos-pct').textContent   = pct(data.naoPagos, total);
        document.getElementById('card-nuncapagos-pct').textContent = pct(data.nuncaPagos, total);
        document.getElementById('card-masculino-pct').textContent  = pct(data.masculino, total);
        document.getElementById('card-feminino-pct').textContent   = pct(data.feminino, total);
    }

    function formatNum(n) { return Number(n).toLocaleString('pt-PT'); }
    function pct(valor, total) {
        if (!total) return '0%';
        return (valor / total * 100).toFixed(1) + '%';
    }

    carregarAnos();
    carregarTudo();
</script>
@endpush