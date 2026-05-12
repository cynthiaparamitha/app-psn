<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikhtisar Tahunan</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 10px;
    }

    .container-ikhtisar {
        width: 100%;
        max-width: 1360px;
        margin: 0 auto;
        padding-top: 15px;
        box-sizing: border-box;
    }

    h2 {
        margin-top: 10px;
        margin-bottom: 18px;
        color: #2c3e50;
        font-size: 22px;
        font-weight: bold;
    }

    h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 15px;
        color: #34495e;
    }

    .filter-box {
        background: #ffffff;
        padding: 12px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .filter-box form {
        display: flex;
        align-items: center;
    }

    select {
        padding: 8px 12px;
        margin-left: 8px;
        border: 1px solid #bbb;
        border-radius: 5px;
        font-size: 14px;
        background: #fff;
    }

    .graph-table-row {
        display: flex;
        flex-direction: column; 
        gap: 20px;
        width: 100%;
        margin-bottom: 20px;
        box-sizing: border-box;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        box-sizing: border-box;
        width: 100%; 
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 260px; 
    }

    .chart-container-large {
        height: 300px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #ddd;
    }

    table th {
        background: #34495e;
        color: white;
        padding: 10px 8px;
        font-size: 13px;
        font-weight: bold;
        border: 1px solid #ddd;
        text-align: center;
    }

    table td {
        padding: 10px 8px;
        border: 1px solid #ddd;
        font-size: 13px;
    }

    table tr:nth-child(even) {
        background: #fafafa;
    }

    table tr:hover {
        background: #eef5ff;
    }

    .right { text-align: right; }

    @media (min-width: 768px) {
        body {
            padding: 20px;
        }
        
        h2 {
            font-size: 24px;
        }
        
        .filter-box {
            width: fit-content;
        }

        .chart-container {
            height: 320px;
        }
    }

    @media (min-width: 1024px) {
        .graph-table-row {
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: stretch;
        }

        .graph-col {
            flex: 0 0 60%;
            width: 60%;
        }

        .table-col {
            flex: 0 0 40%;
            width: 40%;
        }

        .graph-col-2 {
            flex: 0 0 100%;
            width: 100%;
        }

        .graph-col-3 {
            flex: 0 0 calc(50% - 10px);
            width: calc(50% - 10px);
        }

        .chart-container {
            height: 350px;
        }
        
        .chart-container-large {
            height: 380px;
        }
    }
</style>
</head>
<body>

@include('layouts.navbar')

<div class="container-ikhtisar">

    <h2>Ikhtisar Tahunan</h2>

    <div class="filter-box">
        <form method="GET">
            <label><b>Tahun:</b></label>
            <select name="tahun" onchange="this.form.submit()">
                @foreach($listTahun as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="graph-table-row">
        <div class="card graph-col">
            <h3>DRD <?= $tahun ?></h3>
            <div class="chart-container">
                <canvas id="grafikDrd"></canvas>
            </div>
        </div>

        <div class="card table-col">
            <h3>Air Terjual <?= $tahun ?></h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Kubikasi (m³)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($labels as $i => $bulan): ?>
                        <tr>
                            <td><?= $bulan ?></td>
                            <td class="right"><?= number_format($kubik[$i] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr style="background:#27ae60;color:white;font-weight:bold;">
                            <td>Total</td>
                            <td class="right">
                                <?= number_format(array_sum($kubik), 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="graph-table-row">
        <div class="card graph-col-2">
            <h3>Grafik Pemakaian Pelanggan (Per Kubik) <?= $tahun ?></h3>
            <div class="chart-container chart-container-large">
                <canvas id="grafikPemakaian"></canvas>
            </div>
        </div>
    </div>

    <div class="graph-table-row">
        <div class="card graph-col-2">
            <h3>Grafik Penerimaan <?= $tahun ?></h3>
            <div class="chart-container">
                <canvas id="grafikPenerimaan"></canvas>
            </div>
        </div>
    </div> 

    <div class="graph-table-row">
        <div class="card graph-col-3">
            <h3>Efisiensi <?= $tahun ?></h3>
            <div class="chart-container">
                <canvas id="grafikEfisiensi"></canvas>
            </div>
        </div>
        <div class="card graph-col-3">
            <h3>Efektivitas <?= $tahun ?></h3>
            <div class="chart-container">
                <canvas id="grafikEfektivitas"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const standardOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true }}
    };

    const ctx = document.getElementById('grafikDrd').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'DRD <?= $tahun ?>',
                data: <?= json_encode($values) ?>,
                borderWidth: 2,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: standardOptions
    });
</script>

<script>
    const ctxPmk = document.getElementById('grafikPemakaian').getContext('2d');
    new Chart(ctxPmk, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsPemakaian) !!},
            datasets: [
                { label: '0 m³', data: {!! json_encode($k0) !!}, backgroundColor: 'rgba(52,152,219,0.8)' },
                { label: '1–5 m³', data: {!! json_encode($k1_5) !!}, backgroundColor: 'rgba(46,204,113,0.8)' },
                { label: '6–10 m³', data: {!! json_encode($k6_10) !!}, backgroundColor: 'rgba(241,196,15,0.8)' },
                { label: '11–20 m³', data: {!! json_encode($k11_20) !!}, backgroundColor: 'rgba(230,126,34,0.8)' },
                { label: '>20 m³', data: {!! json_encode($k20) !!}, backgroundColor: 'rgba(231,76,60,0.8)' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah Pelanggan' }
                }
            }
        }
    });
</script>

<script>
    const rawLabels = {!! json_encode($labels) !!};
    const rawData   = {!! json_encode($penerimaan) !!};
    let lastIndex = -1;
    rawData.forEach((val, i) => { if (val !== null && val !== 0 && val !== '0') { lastIndex = i; } });

    const labelsTerima = rawLabels.slice(0, lastIndex + 1);
    const dataTerima   = rawData.slice(0, lastIndex + 1);

    const ctxTerima = document.getElementById('grafikPenerimaan').getContext('2d');
    new Chart(ctxTerima, {
        type: 'line',
        data: {
            labels: labelsTerima,
            datasets: [{
                label: 'Penerimaan <?= $tahun ?>',
                data: dataTerima,
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                backgroundColor: 'rgba(54, 162, 235, 0.15)',
                borderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function(value) { return value.toLocaleString('id-ID'); } }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) { return 'Penerimaan <?= $tahun ?> : ' + context.raw.toLocaleString('id-ID'); }
                    }
                }
            }
        }
    });
</script>

<script>
    const ctxEfisiensi = document.getElementById('grafikEfisiensi').getContext('2d');
    new Chart(ctxEfisiensi, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Efisiensi <?= $tahun ?>',
                data: <?= json_encode($efisiensi) ?>,
                borderWidth: 2,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
    });
</script>

<script>
    const ctxEfektivitas = document.getElementById('grafikEfektivitas').getContext('2d');
    new Chart(ctxEfektivitas, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Efektivitas <?= $tahun ?>',
                data: <?= json_encode($efektivitas) ?>,
                borderWidth: 2,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
    });
</script>

</body>
</html>