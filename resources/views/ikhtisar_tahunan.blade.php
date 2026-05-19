<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @env('production')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endenv
    <title>Perumda Tirta Patriot - Ikhtisar Tahunan</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

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

    .loading-placeholder {
        text-align: center;
        padding: 20px;
        font-style: italic;
        color: #7f8c8d;
    }

    @media (min-width: 768px) {
        body { padding: 20px; }
        h2 { font-size: 24px; }
        .filter-box { width: fit-content; }
        .chart-container { height: 320px; }
    }

    @media (min-width: 1024px) {
        .graph-table-row {
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: stretch;
        }
        .graph-col { flex: 0 0 60%; width: 60%; }
        .table-col { flex: 0 0 40%; width: 40%; }
        .graph-col-2 { flex: 0 0 100%; width: 100%; }
        .graph-col-3 { flex: 0 0 calc(50% - 10px); width: calc(50% - 10px); }
        .chart-container { height: 350px; }
        .chart-container-large { height: 380px; }
    }
</style>
</head>
<body>

@include('layouts.navbar')

<div class="container-ikhtisar">

    <h2>Ikhtisar Tahunan</h2>

    <div class="filter-box">
        <form method="GET" onsubmit="event.preventDefault();">
            <label><b>Tahun:</b></label>
            <select name="tahun" id="selectTahun" onchange="fetchDataTahunan(this.value)">
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
            <h3 id="labelTitleDrd">DRD ...</h3>
            <div class="chart-container">
                <canvas id="grafikDrd"></canvas>
            </div>
        </div>

        <div class="card table-col">
            <h3 id="labelTitleAir">Air Terjual ...</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Kubikasi (m³)</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyAir">
                        <tr>
                            <td colspan="2" class="loading-placeholder">Menunggu sinkronisasi data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="graph-table-row">
        <div class="card graph-col-2">
            <h3 id="labelTitlePemakaian">Grafik Pemakaian Pelanggan (Per Kubik) ...</h3>
            <div class="chart-container chart-container-large">
                <canvas id="grafikPemakaian"></canvas>
            </div>
        </div>
    </div>

    <div class="graph-table-row">
        <div class="card graph-col-2">
            <h3 id="labelTitlePenerimaan">Grafik Penerimaan ...</h3>
            <div class="chart-container">
                <canvas id="grafikPenerimaan"></canvas>
            </div>
        </div>
    </div> 

    <div class="graph-table-row">
        <div class="card graph-col-3">
            <h3 id="labelTitleEfisiensi">Efisiensi ...</h3>
            <div class="chart-container">
                <canvas id="grafikEfisiensi"></canvas>
            </div>
        </div>
        <div class="card graph-col-3">
            <h3 id="labelTitleEfektivitas">Efektivitas ...</h3>
            <div class="chart-container">
                <canvas id="grafikEfektivitas"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartDrd = null;
    let chartPemakaian = null;
    let chartPenerimaan = null;
    let chartEfisiensi = null;
    let chartEfektivitas = null;

    function formatRibuan(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function updateComponentTitles(tahunText) {
        document.getElementById('labelTitleDrd').innerText = `DRD ${tahunText}`;
        document.getElementById('labelTitleAir').innerText = `Air Terjual ${tahunText}`;
        document.getElementById('labelTitlePemakaian').innerText = `Grafik Pemakaian Pelanggan (Per Kubik) ${tahunText}`;
        document.getElementById('labelTitlePenerimaan').innerText = `Grafik Penerimaan ${tahunText}`;
        document.getElementById('labelTitleEfisiensi').innerText = `Efisiensi ${tahunText}`;
        document.getElementById('labelTitleEfektivitas').innerText = `Efektivitas ${tahunText}`;
    }

    async function fetchDataTahunan(tahun) {
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tahun=' + tahun;
        window.history.pushState({ path: newUrl, tahun: tahun }, '', newUrl);

        await executeRenderData(tahun);
    }

    async function executeRenderData(tahun) {
        updateComponentTitles(tahun + " (Memuat...)");
        document.getElementById('tableBodyAir').innerHTML = `
            <tr><td colspan="2" class="loading-placeholder">Memuat data...</td></tr>
        `;

        if (chartDrd) { chartDrd.destroy(); chartDrd = null; }
        if (chartPemakaian) { chartPemakaian.destroy(); chartPemakaian = null; }
        if (chartPenerimaan) { chartPenerimaan.destroy(); chartPenerimaan = null; }
        if (chartEfisiensi) { chartEfisiensi.destroy(); chartEfisiensi = null; }
        if (chartEfektivitas) { chartEfektivitas.destroy(); chartEfektivitas = null; }

        try {
            const response = await fetch(`{{ route('ikhtisar.tahunan.api') }}?tahun=${tahun}`);
            if (!response.ok) throw new Error("Gagal mengambil data");
            
            const resData = await response.json();

            updateComponentTitles(tahun);

            let tableRowsHtml = '';
            let totalKubikasi = 0;
            resData.labels.forEach((bulan, index) => {
                let kubikVal = resData.kubik[index] || 0;
                totalKubikasi += kubikVal;
                tableRowsHtml += `
                    <tr>
                        <td>${bulan}</td>
                        <td class="right">${formatRibuan(kubikVal)}</td>
                    </tr>
                `;
            });
            tableRowsHtml += `
                <tr style="background:#27ae60;color:white;font-weight:bold;">
                    <td>Total</td>
                    <td class="right">${formatRibuan(totalKubikasi)}</td>
                </tr>
            `;
            document.getElementById('tableBodyAir').innerHTML = tableRowsHtml;

            chartDrd = new Chart(document.getElementById('grafikDrd').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: resData.labels,
                    datasets: [{
                        label: 'DRD ' + tahun,
                        data: resData.values,
                        borderWidth: 2,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true }}}
            });

            chartPemakaian = new Chart(document.getElementById('grafikPemakaian').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: resData.labelsPemakaian,
                    datasets: [
                        { label: '0 m³', data: resData.k0, backgroundColor: 'rgba(52,152,219,0.8)' },
                        { label: '1–5 m³', data: resData.k1_5, backgroundColor: 'rgba(46,204,113,0.8)' },
                        { label: '6–10 m³', data: resData.k6_10, backgroundColor: 'rgba(241,196,15,0.8)' },
                        { label: '11–20 m³', data: resData.k11_20, backgroundColor: 'rgba(230,126,34,0.8)' },
                        { label: '>20 m³', data: resData.k20, backgroundColor: 'rgba(231,76,60,0.8)' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Jumlah Pelanggan' }}}
                }
            });

            let lastValidIndex = -1;
            resData.penerimaan.forEach((val, i) => { 
                if (val !== null && val !== 0 && val !== '0') { lastValidIndex = i; } 
            });
            const filteredLabels = resData.labels.slice(0, lastValidIndex + 1);
            const filteredPenerimaan = resData.penerimaan.slice(0, lastValidIndex + 1);

            chartPenerimaan = new Chart(document.getElementById('grafikPenerimaan').getContext('2d'), {
                type: 'line',
                data: {
                    labels: filteredLabels,
                    datasets: [{
                        label: 'Penerimaan ' + tahun,
                        data: filteredPenerimaan,
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
                            ticks: { callback: function(v) { return v.toLocaleString('id-ID'); } }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return 'Penerimaan ' + tahun + ' : ' + ctx.raw.toLocaleString('id-ID'); }
                            }
                        }
                    }
                }
            });

            chartEfisiensi = new Chart(document.getElementById('grafikEfisiensi').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: resData.labels,
                    datasets: [{
                        label: 'Efisiensi ' + tahun,
                        data: resData.efisiensi,
                        borderWidth: 2,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
            });

            chartEfektivitas = new Chart(document.getElementById('grafikEfektivitas').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: resData.labels,
                    datasets: [{
                        label: 'Efektivitas ' + tahun,
                        data: resData.efektivitas,
                        borderWidth: 2,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
            });

        } catch (error) {
            console.error("Kesalahan AJAX:", error);
            document.getElementById('tableBodyAir').innerHTML = `
                <tr><td colspan="2" class="loading-placeholder" style="color:red;">Gagal memuat data dari server.</td></tr>
            `;
            updateComponentTitles(tahun + " (Gagal Sinkronisasi)");
        }
    }

    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.tahun) {
            document.getElementById('selectTahun').value = event.state.tahun;
            executeRenderData(event.state.tahun);
        } else {
            const awalTahun = document.getElementById('selectTahun').value;
            executeRenderData(awalTahun);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const tahunAwal = document.getElementById('selectTahun').value;

        const currentUrl = window.location.href;
        window.history.replaceState({ path: currentUrl, tahun: tahunAwal }, '', currentUrl);

        executeRenderData(tahunAwal);
    });
</script>
</body>
</html>