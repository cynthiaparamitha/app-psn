<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LHK PSN - Detail Zona</title>

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

            .action-row {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
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

    <h2>Laporan Harian Kas - Zona {{ $zona ?? '' }}</h2>

    <div class="action-row">
        <a href="/lhk?tabul={{ $tabul }}" class="back-btn">← Kembali ke Zona</a>

        <div class="filter-box">
            <form method="GET">
                <input type="hidden" name="zona" value="{{ $zona }}">
                <label><b>Periode:</b></label>
                <select name="tabul" onchange="this.form.submit()">
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
    </div>

    @php
        $totalAir = 0; 
        $totalAdm = 0; 
        $totalDenda = 0; 
        $totalNAL = 0; 
        $totalAll = 0;
    @endphp

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>K. Pelayanan</th>
                            <th>Air</th>
                            <th>Administrasi</th>
                            <th>Denda</th>
                            <th>NAL</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                            @php
                                $totalAir += $row->air;
                                $totalAdm += $row->administrasi;
                                $totalDenda += $row->denda;
                                $totalNAL += $row->NAL;
                                $totalAll += $row->total;
                            @endphp
                            <tr>
                                <td>{{ $row->cabang }}</td>
                                <td class="right">{{ number_format($row->air, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->administrasi, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->denda, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->NAL, 0, ',', '.') }}</td>
                                <td class="right">{{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td>Total</td>
                            <td class="right">{{ number_format($totalAir, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totalAdm, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totalDenda, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totalNAL, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($totalAll, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>