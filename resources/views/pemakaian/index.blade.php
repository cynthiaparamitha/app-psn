<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perumda Tirta Patriot - Laporan Pemakaian PSN</title>

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

        .total-row {
            background: #27ae60 !important;
            color: white;
            font-weight: bold;
        }

        .loading-placeholder {
            text-align: center;
            padding: 24px;
            font-style: italic;
            color: #7f8c8d;
        }

        @media (min-width: 768px) {
            body { padding: 20px; }
            h2 { font-size: 24px; }
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

    <h2>Laporan Pemakaian</h2>

    <div class="filter-box">
        <form method="GET" onsubmit="event.preventDefault();">
            <label><b>Periode:</b></label>
            <select name="tabul" id="selectTabul" onchange="changePeriode(this.value)">
                @php
                    $bulanSingkat = [
                        '01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
                        '05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Ags',
                        '09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
                    ];
                @endphp

                @foreach($listTabul as $t)
                    @php
                        $tahun = substr($t->tabul,0,4);
                        $bulan = substr($t->tabul,4,2);
                        $label = $bulanSingkat[$bulan].' '.$tahun;
                    @endphp
                    <option value="{{ $t->tabul }}" {{ $tabul == $t->tabul ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">Zona</th>
                            <th colspan="3">PEMAKAIAN 0 M³</th>
                            <th colspan="3">PEMAKAIAN 1–5 M³</th>
                            <th colspan="3">PEMAKAIAN 6–10 M³</th>
                            <th colspan="3">PEMAKAIAN 11–20 M³</th>
                            <th colspan="3">PEMAKAIAN > 20 M³</th>
                        </tr>
                        <tr>
                            <th>PLG</th><th>M³</th><th>REK AIR + ADM</th>
                            <th>PLG</th><th>M³</th><th>REK AIR + ADM</th>
                            <th>PLG</th><th>M³</th><th>REK AIR + ADM</th>
                            <th>PLG</th><th>M³</th><th>REK AIR + ADM</th>
                            <th>PLG</th><th>M³</th><th>REK AIR + ADM</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyPemakaian">
                        <tr>
                            <td colspan="16" class="loading-placeholder">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function formatRibuan(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function changePeriode(tabul) {
        const queryParams = `?tabul=${tabul}`;
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + queryParams;
        
        window.history.pushState({ tabul: tabul }, '', newUrl);

        executeFetchPemakaian(tabul);
    }

    async function executeFetchPemakaian(tabul) {
        const tbody = document.getElementById('tableBodyPemakaian');
        tbody.innerHTML = `<tr><td colspan="16" class="loading-placeholder">Memuat data...</td></tr>`;

        try {
            const response = await fetch(`{{ route('pemakaian.api') }}?tabul=${tabul}`);
            if (!response.ok) throw new Error("Gagal memuat respons database");

            const res = await response.json();
            
            let rowsHtml = '';
            
            let total = {
                plg0: 0, m30: 0, tag0: 0,
                plg1: 0, m31: 0, tag1: 0,
                plg6: 0, m36: 0, tag6: 0,
                plg11: 0, m311: 0, tag11: 0,
                plg21: 0, m321: 0, tag21: 0
            };

            if (res.data.length === 0) {
                rowsHtml = `<tr><td colspan="16" class="loading-placeholder">Tidak ada data pemakaian untuk periode ini.</td></tr>`;
            } else {
                res.data.forEach(row => {
                const plg0 = parseInt(row.jumlah_plg_k_0) || 0;
                const m30  = parseInt(row.kubik_0) || 0;
                const tag0 = parseInt(row.tagihan_k_0) || 0;

                const plg1 = parseInt(row.jumlah_plg_k_1_5) || 0;
                const m31  = parseInt(row.kubik_1_5) || 0;
                const tag1 = parseInt(row.tagihan_k_1_5) || 0;

                const plg6 = parseInt(row.jumlah_plg_k_6_10) || 0;
                const m36  = parseInt(row.kubik_6_10) || 0;
                const tag6 = parseInt(row.tagihan_k_6_10) || 0;

                const plg11 = parseInt(row.jumlah_plg_k_11_20) || 0;
                const m311  = parseInt(row.kubik_11_20) || 0;
                const tag11 = parseInt(row.tagihan_k_11_20) || 0;

                const plg21 = parseInt(row.jumlah_plg_lbh_20) || 0;
                const m321  = parseInt(row.kubik_lbh_20) || 0;
                const tag21 = parseInt(row.tagihan_lbh_20) || 0;

                total.plg0 += plg0; total.m30 += m30; total.tag0 += tag0;
                total.plg1 += plg1; total.m31 += m31; total.tag1 += tag1;
                total.plg6 += plg6; total.m36 += m36; total.tag6 += tag6;
                total.plg11 += plg11; total.m311 += m311; total.tag11 += tag11;
                total.plg21 += plg21; total.m321 += m321; total.tag21 += tag21;

                    rowsHtml += `
                        <tr>
                            <td>${row.zona_cd}</td>
                            <td class="right">${formatRibuan(plg0)}</td>
                            <td class="right">${formatRibuan(m30)}</td>
                            <td class="right">${formatRibuan(tag0)}</td>
                            
                            <td class="right">${formatRibuan(plg1)}</td>
                            <td class="right">${formatRibuan(m31)}</td>
                            <td class="right">${formatRibuan(tag1)}</td>
                            
                            <td class="right">${formatRibuan(plg6)}</td>
                            <td class="right">${formatRibuan(m36)}</td>
                            <td class="right">${formatRibuan(tag6)}</td>
                            
                            <td class="right">${formatRibuan(plg11)}</td>
                            <td class="right">${formatRibuan(m311)}</td>
                            <td class="right">${formatRibuan(tag11)}</td>
                            
                            <td class="right">${formatRibuan(plg21)}</td>
                            <td class="right">${formatRibuan(m321)}</td>
                            <td class="right">${formatRibuan(tag21)}</td>
                        </tr>
                    `;
                });

                rowsHtml += `
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="right">${formatRibuan(total.plg0)}</td>
                        <td class="right">${formatRibuan(total.m30)}</td>
                        <td class="right">${formatRibuan(total.tag0)}</td>

                        <td class="right">${formatRibuan(total.plg1)}</td>
                        <td class="right">${formatRibuan(total.m31)}</td>
                        <td class="right">${formatRibuan(total.tag1)}</td>

                        <td class="right">${formatRibuan(total.plg6)}</td>
                        <td class="right">${formatRibuan(total.m36)}</td>
                        <td class="right">${formatRibuan(total.tag6)}</td>

                        <td class="right">${formatRibuan(total.plg11)}</td>
                        <td class="right">${formatRibuan(total.m311)}</td>
                        <td class="right">${formatRibuan(total.tag11)}</td>

                        <td class="right">${formatRibuan(total.plg21)}</td>
                        <td class="right">${formatRibuan(total.m321)}</td>
                        <td class="right">${formatRibuan(total.tag21)}</td>
                    </tr>
                `;
            }

            tbody.innerHTML = rowsHtml;

        } catch (error) {
            console.error(error);
            tbody.innerHTML = `
                <tr><td colspan="16" class="loading-placeholder" style="color:red; font-weight:bold;">
                    Gagal memuat data dari server.
                </td></tr>
            `;
        }
    }

    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.tabul) {
            document.getElementById('selectTabul').value = event.state.tabul;
            executeFetchPemakaian(event.state.tabul);
        } else {
            const defaultTabul = document.getElementById('selectTabul').value;
            executeFetchPemakaian(defaultTabul);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const initialTabul = document.getElementById('selectTabul').value;
        
        window.history.replaceState({ tabul: initialTabul }, '', window.location.href);
        
        executeFetchPemakaian(initialTabul);
    });
</script>
</body>
</html>