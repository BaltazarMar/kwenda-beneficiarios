@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- TOPO: TÍTULO + FILTROS --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0"> </h2>

        <div class="d-flex align-items-center gap-2 flex-wrap">

            {{-- Filtro de Ano --}}
            <select id="filtro-ano" class="form-select form-select-sm w-auto">
                <option value="">Todos os anos</option>
            </select>

            {{-- Badge município activo --}}
            <div id="filtro-activo" class="d-none d-flex align-items-center gap-2">
                <span class="badge bg-info fs-6" id="municipio-label"></span>
                <button class="btn btn-sm btn-outline-danger" id="btn-limpar">✕ Limpar filtro</button>
            </div>

        </div>
    </div>

    {{-- CARDS LINHA 1 --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">Total de Beneficiários</h6>
                    <h2 class="card-text" id="card-total">{{ number_format($total, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h6 class="card-title">Pagos</h6>
                    <h2 class="card-text" id="card-pagos">{{ number_format($pagos, 0, ',', '.') }}</h2>
                    <small id="card-pagos-pct">{{ $total > 0 ? round(($pagos / $total) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h6 class="card-title">Não Pagos</h6>
                    <h2 class="card-text" id="card-naopagos">{{ number_format($naoPagos, 0, ',', '.') }}</h2>
                    <small id="card-naopagos-pct">{{ $total > 0 ? round(($naoPagos / $total) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h6 class="card-title">Nunca Pagos</h6>
                    <h2 class="card-text" id="card-nuncapagos">{{ number_format($nuncaPagos, 0, ',', '.') }}</h2>
                    <small id="card-nuncapagos-pct">{{ $total > 0 ? round(($nuncaPagos / $total) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>

    </div>

    {{-- CARDS LINHA 2 --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">Masculino</h6>
                    <h2 class="text-info" id="card-masculino">{{ number_format($masculino, 0, ',', '.') }}</h2>
                    <small class="text-muted" id="card-masculino-pct">{{ $total > 0 ? round(($masculino / $total) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">Feminino</h6>
                    <h2 style="color:#e91e8c;" id="card-feminino">{{ number_format($feminino, 0, ',', '.') }}</h2>
                    <small class="text-muted" id="card-feminino-pct">{{ $total > 0 ? round(($feminino / $total) * 100, 1) : 0 }}%</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card h-100 border-warning">
                <div class="card-body">
                    <h6 class="card-title text-muted">Bairros</h6>
                    <h2 class="text-warning" id="card-bairros">{{ number_format($bairros, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-white bg-dark h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Arrecadado</h6>
                    <h2 class="card-text" id="card-valor">{{ number_format($valorTotal, 0, ',', '.') }} Kz</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- GRÁFICOS --}}
    <div class="row g-3">

        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    Distribuição por Município
                    <small class="text-muted ms-2">(clica numa barra para filtrar)</small>
                </div>
                <div class="card-body">
                    <canvas id="graficoMunicipio"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Recorrências pagas <span id="label-municipio-rec" class="text-primary fw-bold"></span></span>
                </div>
                <div class="card-body">
                    <canvas id="graficoRecorrencias"></canvas>
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
    let anoActivo       = null;

    const dadosMunicipios = {
        labels: {!! json_encode($porMunicipio->keys()) !!},
        values: {!! json_encode($porMunicipio->values()) !!}
    };

    // ======= PLUGIN: NÚMEROS EM CIMA DAS BARRAS =======
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
                    ctx.fillStyle    = '#333333';
                    ctx.textAlign    = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillText(Number(value).toLocaleString('pt-PT'), bar.x, bar.y - 4);
                });
            });
            ctx.restore();
        }
    };

    // ======= GRÁFICO MUNICÍPIOS =======
    const ctxMunicipio = document.getElementById('graficoMunicipio').getContext('2d');
    const graficoMunicipio = new Chart(ctxMunicipio, {
        type: 'bar',
        plugins: [pluginNumeros],
        data: {
            labels: dadosMunicipios.labels,
            datasets: [{
                label: 'Beneficiários',
                data: dadosMunicipios.values,
                backgroundColor: dadosMunicipios.labels.map(() => 'rgba(13, 110, 253, 0.7)'),
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            layout: { padding: { top: 20 } },
            scales: {
                x: {
                    ticks: { maxRotation: 45, minRotation: 45, font: { size: 11 } },
                    grid: { display: false }
                },
                y: { display: false, beginAtZero: true }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const index     = elements[0].index;
                    const municipio = graficoMunicipio.data.labels[index];
                    filtrarPorMunicipio(municipio);
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
            }
        }
    });

    // ======= GRÁFICO RECORRÊNCIAS =======
    const ctxRec = document.getElementById('graficoRecorrencias').getContext('2d');
    const graficoRecorrencias = new Chart(ctxRec, {
        type: 'bar',
        plugins: [pluginNumeros],
        data: {
            labels: ['Rec 1', 'Rec 2', 'Rec 3', 'Rec 4', 'Rec 5', 'Rec 6'],
            datasets: [{
                label: 'Beneficiários pagos',
                data: [0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                borderRadius: 4
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

    // ======= CARREGAR ANOS NO SELECT =======
    function carregarAnos() {
        fetch('/dashboard-recorrencias')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('filtro-ano');
                select.innerHTML = '<option value="">Todos os anos</option>';
                data.anos.forEach(ano => {
                    const opt       = document.createElement('option');
                    opt.value       = ano;
                    opt.textContent = ano;
                    select.appendChild(opt);
                });
            });
    }

    // ======= CARREGAR RECORRÊNCIAS =======
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

    // ======= CARREGAR TUDO =======
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
                    m === municipioActivo ? 'rgba(255, 193, 7, 0.9)' : 'rgba(13, 110, 253, 0.7)'
                );
                graficoMunicipio.update();
            });

        carregarRecorrencias();
    }

    // ======= FILTRO DE ANO =======
    document.getElementById('filtro-ano').addEventListener('change', function() {
        anoActivo = this.value || null;
        carregarTudo();
    });

    // ======= FILTRAR POR MUNICÍPIO =======
    function filtrarPorMunicipio(municipio) {
        municipioActivo = municipio;

        document.getElementById('filtro-activo').classList.remove('d-none');
        document.getElementById('municipio-label').textContent = '📍 ' + municipio;

        graficoMunicipio.data.datasets[0].backgroundColor = graficoMunicipio.data.labels.map(m =>
            m === municipio ? 'rgba(255, 193, 7, 0.9)' : 'rgba(13, 110, 253, 0.3)'
        );
        graficoMunicipio.update();

        carregarRecorrencias();

        let url = `/dashboard-filtros?municipio=${encodeURIComponent(municipio)}`;
        if (anoActivo) url += `&ano=${anoActivo}`;

        fetch(url)
            .then(res => res.json())
            .then(data => actualizarDashboard(data));
    }

    // ======= LIMPAR FILTRO MUNICÍPIO =======
    document.getElementById('btn-limpar').addEventListener('click', () => {
        municipioActivo = null;
        document.getElementById('filtro-activo').classList.add('d-none');
        carregarTudo();
    });

    // ======= ACTUALIZAR CARDS =======
    function actualizarDashboard(data) {
        const total = data.total;

        document.getElementById('card-total').textContent      = formatNum(total);
        document.getElementById('card-pagos').textContent      = formatNum(data.pagos);
        document.getElementById('card-naopagos').textContent   = formatNum(data.naoPagos);
        document.getElementById('card-nuncapagos').textContent = formatNum(data.nuncaPagos);
        document.getElementById('card-masculino').textContent  = formatNum(data.masculino);
        document.getElementById('card-feminino').textContent   = formatNum(data.feminino);
        document.getElementById('card-bairros').textContent    = formatNum(data.bairros); // ← bairros
        document.getElementById('card-valor').textContent      = formatNum(data.valorTotal) + ' Kz';

        document.getElementById('card-pagos-pct').textContent      = pct(data.pagos, total);
        document.getElementById('card-naopagos-pct').textContent   = pct(data.naoPagos, total);
        document.getElementById('card-nuncapagos-pct').textContent = pct(data.nuncaPagos, total);
        document.getElementById('card-masculino-pct').textContent  = pct(data.masculino, total);
        document.getElementById('card-feminino-pct').textContent   = pct(data.feminino, total);
    }

    // ======= HELPERS =======
    function formatNum(n) {
        return Number(n).toLocaleString('pt-PT');
    }

    function pct(valor, total) {
        if (!total) return '0%';
        return (valor / total * 100).toFixed(1) + '%';
    }

    // Inicializa
    carregarAnos();
    carregarTudo();
</script>
@endpush