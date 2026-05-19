<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @env('production')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endenv
    <title>Perumda Tirta Patriot - Rekap Pelanggan PSN</title>
    
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
            display: inline-block;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 10px;
        }
        .back-btn {
            display: inline-block;
            padding: 8px 18px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            box-sizing: border-box;
            transition: background 0.2s ease;
        }
        .back-btn:hover {
            background: #2980b9;
        }
        .report-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            width: 100%;
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
        .section-title {
            margin-bottom: 15px;
            font-size: 16px;
            color: #2c3e50;
            border-left: 4px solid #3498db;
            padding-left: 10px;
            font-weight: bold;
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
            padding: 12px 15px;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
        }
        table td {
            padding: 10px 15px;
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
        .total-row {
            font-weight: bold;
            background: #eaeded !important;
        }
        .total-box {
            font-size: 32px;
            font-weight: bold;
            color: #27ae60;
            text-align: center;
            padding: 15px 0;
        }

        @media (min-width: 768px) {
            body {
                padding: 20px;
            }
            h2 {
                font-size: 24px;
            }
            .report-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .total-card-col {
                grid-column: span 2;
            }
        }
        @media (min-width: 1024px) {
            .container-drd {
                padding-top: 10px;
            }
            h2 {
                font-size: 26px;
            }
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container-drd">
    <div class="header-container">
        <h2>📊 Rekapan Pelanggan</h2>
        <a href="javascript:history.back()" class="back-btn">← Kembali</a>
    </div>

    <div class="report-grid">
        <div class="card">
            <div class="section-title">Rekap Berdasarkan Tarif</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tarif</th>
                            <th>Jumlah Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTarif">
                        <tr><td colspan="2">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="section-title">Rekap Berdasarkan Kantor Pelayanan</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Kantor Pelayanan</th>
                            <th>Jumlah Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody id="bodyCabang">
                        <tr><td colspan="2">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="section-title">Rekap Berdasarkan Zona</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Zona</th>
                            <th>Jumlah Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody id="bodyZona">
                        <tr><td colspan="2">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="section-title">Rekap Berdasarkan Status</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody id="bodyStatus">
                        <tr><td colspan="2">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card total-card-col">
            <div class="section-title">Total Keseluruhan Pelanggan</div>
            <div class="total-box" id="totalBox">0</div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);

        fetch("{{ route('pelanggan.rekap.api') }}?" + urlParams.toString())
            .then(response => response.json())
            .then(res => {
                renderTableGroup(res.rekapTarif, 'bodyTarif');
                renderTableGroup(res.rekapCabang, 'bodyCabang');
                renderTableGroup(res.rekapZona, 'bodyZona');
                renderTableGroup(res.rekapStatus, 'bodyStatus');

                document.getElementById('totalBox').innerText = Number(res.total).toLocaleString('id-ID');
            })
            .catch(err => {
                console.error(err);
                ['bodyTarif', 'bodyCabang', 'bodyZona', 'bodyStatus'].forEach(id => {
                    document.getElementById(id).innerHTML = '<tr><td colspan="2" style="color:red;">Gagal memuat rekap data.</td></tr>';
                });
            });
    });

    function renderTableGroup(groupData, targetElementId) {
        const tbody = document.getElementById(targetElementId);
        tbody.innerHTML = '';

        let sum = 0;
        const keys = Object.keys(groupData);

        if (keys.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" style="text-align:center;">Tidak ada data</td></tr>';
            return;
        }

        keys.forEach(key => {
            let count = groupData[key];
            sum += count;

            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${key === "" || key === "null" ? '[Tanpa Nama]' : key}</td>
                <td>${Number(count).toLocaleString('id-ID')}</td>
            `;
            tbody.appendChild(tr);
        });

        let totalTr = document.createElement('tr');
        totalTr.className = 'total-row';
        totalTr.innerHTML = `
            <td>Total</td>
            <td>${Number(sum).toLocaleString('id-ID')}</td>
        `;
        tbody.appendChild(totalTr);
    }
</script>
</body>
</html>