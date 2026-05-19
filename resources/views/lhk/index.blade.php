<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @env('production')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endenv
    <title>Perumda Tirta Patriot - LHK PSN</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 10px;
        }

        .container-drd {
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

        .action-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            background: #2980b9;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: background 0.2s;
            width: fit-content;
            box-sizing: border-box;
            cursor: pointer;
        }
        .back-btn:hover {
            background: #1f6aa5;
        }

        .filter-box {
            background: #ffffff;
            padding: 12px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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

        .report-row {
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
            padding: 12px 10px;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #ddd;
            text-align: center;
            white-space: nowrap;
        }

        table td {
            padding: 10px 10px;
            border: 1px solid #ddd;
            font-size: 13px;
            white-space: nowrap;
        }

        table tr:nth-child(even) {
            background: #fafafa;
        }

        table tr:hover {
            background: #eef5ff;
        }

        .right { 
            text-align: right; 
            font-variant-numeric: tabular-nums;
        }

        .zona-link {
            font-weight: bold;
            color: #2980b9;
            text-decoration: none;
            cursor: pointer;
        }
        
        .zona-link:hover {
            text-decoration: underline;
        }

        .total-row {
            background: #27ae60 !important;
            color: white;
            font-weight: bold;
        }

        .loading-placeholder {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #7f8c8d;
        }

        @media (min-width: 768px) {
            body { padding: 20px; }
            h2 { font-size: 24px; }
            .action-row {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
            .filter-box { width: fit-content; }
        }

        @media (min-width: 1024px) {
            .container-drd { padding-top: 10px; }
            h2 { font-size: 26px; }
            .card { padding: 20px; }
            .report-row { flex-direction: row; flex-wrap: nowrap; }
            .table-col { flex: 0 0 100%; width: 100%; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container-drd">

    <h2 id="pageTitle">Laporan Harian Kas</h2>

    <div class="action-row">
        <div class="filter-box">
            <form method="GET" onsubmit="event.preventDefault();">
                <label><b>Periode:</b></label>
                <select name="tabul" id="selectTabul" onchange="changePeriode(this.value)">
                    @php
                        $bulanSingkat = [
                            '01' => 'Jan','02' => 'Feb','03' => 'Mar','04' => 'Apr',
                            '05' => 'Mei','06' => 'Jun','07' => 'Jul','08' => 'Ags',
                            '09' => 'Sep','10' => 'Okt','11' => 'Nov','12' => 'Des'
                        ];
                    @endphp

                    @foreach($listTabul as $t)
                        @php
                            $tahun = substr($t->tabul, 0, 4);
                            $bulan = substr($t->tabul, 4, 2);
                            $label = $bulanSingkat[$bulan] . ' ' . $tahun;
                        @endphp
                        <option value="{{ $t->tabul }}" {{ $tabul == $t->tabul ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div id="spacer" style="flex: 1;"></div>

        <a id="btnBack" onclick="navigateLhk(document.getElementById('selectTabul').value, '')" class="back-btn" style="display: none;">← Kembali ke Zona</a>
    </div>

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th id="thFirstColumn">Zona</th>
                            <th>Air</th>
                            <th>Administrasi</th>
                            <th>Denda</th>
                            <th>NAL</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyLhk">
                        <tr>
                            <td colspan="6" class="loading-placeholder">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    let currentZona = "{{ $zona ?? '' }}";

    function formatRibuan(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function changePeriode(tabul) {
        navigateLhk(tabul, currentZona);
    }

    async function navigateLhk(tabul, zona) {
        currentZona = zona;

        let queryParams = `?tabul=${tabul}`;
        if (zona) {
            queryParams += `&zona=${encodeURIComponent(zona)}`;
        }

        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + queryParams;
        
        window.history.pushState({ tabul: tabul, zona: zona }, '', newUrl);

        await executeFetchLhk(tabul, zona);
    }

    async function executeFetchLhk(tabul, zona) {
        document.getElementById('tableBodyLhk').innerHTML = `
            <tr><td colspan="6" class="loading-placeholder">Memuat data...</td></tr>
        `;

        try {
            const response = await fetch(`{{ route('lhk.api') }}?tabul=${tabul}&zona=${encodeURIComponent(zona)}`);
            if (!response.ok) throw new Error("Gagal mengambil data");
            
            const res = await response.json();

            if (res.mode === 'cabang') {
                document.getElementById('pageTitle').innerText = `Laporan Harian Kas - Zona ${res.zona}`;
                document.getElementById('thFirstColumn').innerText = "K. Pelayanan";
                document.getElementById('btnBack').style.display = "inline-flex";
                document.getElementById('spacer').style.display = "none";
            } else {
                document.getElementById('pageTitle').innerText = "Laporan Harian Kas";
                document.getElementById('thFirstColumn').innerText = "Zona";
                document.getElementById('btnBack').style.display = "none";
                document.getElementById('spacer').style.display = "block";
            }

            let rowsHtml = '';
            let totalAir = 0, totalAdm = 0, totalDenda = 0, totalNAL = 0, totalAll = 0;
            let jumlahBaris = res.data.length;

            if (jumlahBaris === 0) {
                rowsHtml = `<tr><td colspan="6" class="loading-placeholder">Tidak ada data pada periode ini.</td></tr>`;
            } else {
                res.data.forEach(row => {
                    totalAir += parseFloat(row.air ?? 0);
                    totalAdm += parseFloat(row.administrasi ?? 0);
                    totalDenda += parseFloat(row.denda ?? 0);
                    totalNAL += parseFloat(row.NAL ?? 0);
                    totalAll += parseFloat(row.total ?? 0);

                    let firstColHtml = '';
                    if (res.mode === 'zona') {
                        firstColHtml = `<a class="zona-link" onclick="navigateLhk('${res.tabul}', '${row.zona}')">${row.zona}</a>`;
                    } else {
                        firstColHtml = row.cabang;
                    }

                    rowsHtml += `
                        <tr>
                            <td>${firstColHtml}</td>
                            <td class="right">${formatRibuan(row.air)}</td>
                            <td class="right">${formatRibuan(row.administrasi)}</td>
                            <td class="right">${formatRibuan(row.denda)}</td>
                            <td class="right">${formatRibuan(row.NAL)}</td>
                            <td class="right">${formatRibuan(row.total)}</td>
                        </tr>
                    `;
                });

                rowsHtml += `
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="right">${formatRibuan(totalAir)}</td>
                        <td class="right">${formatRibuan(totalAdm)}</td>
                        <td class="right">${formatRibuan(totalDenda)}</td>
                        <td class="right">${formatRibuan(totalNAL)}</td>
                        <td class="right">${formatRibuan(totalAll)}</td>
                    </tr>
                `;
            }

            document.getElementById('tableBodyLhk').innerHTML = rowsHtml;

        } catch (error) {
            console.error(error);
            document.getElementById('tableBodyLhk').innerHTML = `
                <tr><td colspan="6" class="loading-placeholder" style="color:red;">Gagal memuat data dari server.</td></tr>
            `;
        }
    }

    window.addEventListener('popstate', function(event) {
        if (event.state) {
            const tabul = event.state.tabul;
            const zona = event.state.zona || '';
            document.getElementById('selectTabul').value = tabul;
            currentZona = zona;
            executeFetchLhk(tabul, zona);
        } else {
            const defaultTabul = document.getElementById('selectTabul').value;
            currentZona = "{{ $zona ?? '' }}";
            executeFetchLhk(defaultTabul, currentZona);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const initialTabul = document.getElementById('selectTabul').value;
        const initialZona = "{{ $zona ?? '' }}";

        window.history.replaceState({ tabul: initialTabul, zona: initialZona }, '', window.location.href);
        
        executeFetchLhk(initialTabul, initialZona);
    });
</script>
</body>
</html>