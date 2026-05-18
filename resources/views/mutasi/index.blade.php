<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perumda Tirta Patriot - Laporan Mutasi PSN</title>

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

    <h2>Laporan Mutasi</h2>

    <div class="filter-box">
        <form method="GET" onsubmit="event.preventDefault();">
            <label><b>Periode:</b></label>
            <select name="tabul" id="selectTabul" onchange="changePeriode(this.value)">
                @php
                    $bulanSingkat = [
                        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
                        '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags',
                        '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des',
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

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>K. Pelayanan</th>
                            <th>Golongan</th>
                            <th>Alamat Plg</th>
                            <th>Ganti Meter</th>
                            <th>PK</th>
                            <th>Plg Baru</th>
                            <th>Aktif ke Nonaktif</th>
                            <th>Nama Plg</th>
                            <th>Stand meter</th>
                            <th>Ganti Nopel</th>
                            <th>No. HP</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyMutasi">
                        <tr>
                            <td colspan="11" class="loading-placeholder">Memuat data...</td>
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

        executeFetchMutasi(tabul);
    }

    async function executeFetchMutasi(tabul) {
        const tbody = document.getElementById('tableBodyMutasi');
        tbody.innerHTML = `<tr><td colspan="11" class="loading-placeholder">Memuat data...</td></tr>`;

        try {
            const response = await fetch(`{{ route('mutasi.api') }}?tabul=${tabul}`);
            if (!response.ok) throw new Error("Gagal memuat respons database");

            const res = await response.json();
            
            let rowsHtml = '';
            
            let total = {
                golongan: 0, alamat_pelanggan: 0, ganti_meter: 0,
                pengaktifan_kembali: 0, pelanggan_baru: 0, aktif_ke_nonaktif: 0,
                nama_pelanggan: 0, stand_meter: 0, ganti_nopel: 0, no_handphone: 0
            };

            if (res.data.length === 0) {
                rowsHtml = `<tr><td colspan="11" class="loading-placeholder">Tidak ada data mutasi untuk periode ini.</td></tr>`;
            } else {
                res.data.forEach(row => {
                    const gol = parseInt(row.golongan) || 0;
                    const alm = parseInt(row.alamat_pelanggan) || 0;
                    const mtr = parseInt(row.ganti_meter) || 0;
                    const pnk = parseInt(row.pengaktifan_kembali) || 0;
                    const baru = parseInt(row.pelanggan_baru) || 0;
                    const akn = parseInt(row.aktif_ke_nonaktif) || 0;
                    const nma = parseInt(row.nama_pelanggan) || 0;
                    const std = parseInt(row.stand_meter) || 0;
                    const npl = parseInt(row.ganti_nopel) || 0;
                    const hp = parseInt(row.no_handphone) || 0;

                    total.golongan += gol;
                    total.alamat_pelanggan += alm;
                    total.ganti_meter += mtr;
                    total.pengaktifan_kembali += pnk;
                    total.pelanggan_baru += baru;
                    total.aktif_ke_nonaktif += akn;
                    total.nama_pelanggan += nma;
                    total.stand_meter += std;
                    total.ganti_nopel += npl;
                    total.no_handphone += hp;

                    rowsHtml += `
                        <tr>
                            <td>${row.cabang ?? '-'}</td>
                            <td class="right">${formatRibuan(gol)}</td>
                            <td class="right">${formatRibuan(alm)}</td>
                            <td class="right">${formatRibuan(mtr)}</td>
                            <td class="right">${formatRibuan(pnk)}</td>
                            <td class="right">${formatRibuan(baru)}</td>
                            <td class="right">${formatRibuan(akn)}</td>
                            <td class="right">${formatRibuan(nma)}</td>
                            <td class="right">${formatRibuan(std)}</td>
                            <td class="right">${formatRibuan(npl)}</td>
                            <td class="right">${formatRibuan(hp)}</td>
                        </tr>
                    `;
                });

                rowsHtml += `
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="right">${formatRibuan(total.golongan)}</td>
                        <td class="right">${formatRibuan(total.alamat_pelanggan)}</td>
                        <td class="right">${formatRibuan(total.ganti_meter)}</td>
                        <td class="right">${formatRibuan(total.pengaktifan_kembali)}</td>
                        <td class="right">${formatRibuan(total.pelanggan_baru)}</td>
                        <td class="right">${formatRibuan(total.aktif_ke_nonaktif)}</td>
                        <td class="right">${formatRibuan(total.nama_pelanggan)}</td>
                        <td class="right">${formatRibuan(total.stand_meter)}</td>
                        <td class="right">${formatRibuan(total.ganti_nopel)}</td>
                        <td class="right">${formatRibuan(total.no_handphone)}</td>
                    </tr>
                `;
            }

            tbody.innerHTML = rowsHtml;

        } catch (error) {
            console.error(error);
            tbody.innerHTML = `
                <tr><td colspan="11" class="loading-placeholder" style="color:red; font-weight:bold;">
                    Gagal mengambil data dari server. Silakan muat ulang halaman.
                </td></tr>
            `;
        }
    }

    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.tabul) {
            document.getElementById('selectTabul').value = event.state.tabul;
            executeFetchMutasi(event.state.tabul);
        } else {
            const defaultTabul = document.getElementById('selectTabul').value;
            executeFetchMutasi(defaultTabul);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const initialTabul = document.getElementById('selectTabul').value;
        
        window.history.replaceState({ tabul: initialTabul }, '', window.location.href);
        
        executeFetchMutasi(initialTabul);
    });
</script>
</body>
</html>