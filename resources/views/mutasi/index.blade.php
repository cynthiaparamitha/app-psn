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

            .report-row {
                flex-direction: row;
                flex-wrap: nowrap;
            }

            .table-col {
                flex: 0 0 100%;
                width: 100%;
            }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container-drd">

    <h2>Laporan Mutasi</h2>

    <div class="filter-box">
        <form method="GET">
            <label><b>Periode:</b></label>
            <select name="tabul" onchange="this.form.submit()">
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
                    <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>{{ $row->cabang }}</td>
                                <td class="right">{{ number_format($row->golongan, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->alamat_pelanggan, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->ganti_meter, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->pengaktifan_kembali, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->pelanggan_baru, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->aktif_ke_nonaktif, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->nama_pelanggan, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->stand_meter, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->ganti_nopel, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->no_handphone, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td>Total</td>
                            <td class="right">{{ number_format($totals['golongan'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['alamat_pelanggan'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['ganti_meter'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['pengaktifan_kembali'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['pelanggan_baru'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['aktif_ke_nonaktif'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['nama_pelanggan'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['stand_meter'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['ganti_nopel'], 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totals['no_handphone'], 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>