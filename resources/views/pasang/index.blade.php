<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perumda Tirta Patriot - Progress Pemasangan PSN</title>

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

        .left {
            text-align: left;
        }

        .progress {
            font-weight: bold;
            color: #2c3e50;
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

    <h2>Progress Pemasangan Per Zona</h2>

    <div class="filter-box">
        <form method="GET">
            <label><b>Periode:</b></label>
            <select name="tabul" onchange="this.form.submit()">
                @php
                    $bulanSingkat = [
                        '01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
                        '05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Ags',
                        '09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
                    ];
                @endphp

                @foreach($listTabul as $t)
                    @php
                        $th = substr($t->tabul, 0, 4);
                        $bl = substr($t->tabul, 4, 2);
                    @endphp
                    <option value="{{ $t->tabul }}" {{ $tabul == $t->tabul ? 'selected' : '' }}>
                        {{ $bulanSingkat[$bl] }} {{ $th }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @php
        function romawi($angka) {
            $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV'];
            return $map[$angka] ?? $angka;
        }

        $grandTotalPendaftar = 0;
        $grandTotalPasang = 0;
        $grandTotalMutasi = 0;
    @endphp

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Zona</th>
                            <th>Progress Pasang</th>
                            <th>Pendaftar</th>
                            <th>Pasang</th>
                            <th>Mutasi Pelanggan Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $i => $row)
                            @php
                                $pendaftar = $row->Pendaftar ?? 0;
                                $pasang    = $row->Terpasang ?? 0;
                                $mutasi    = $row->Mutasi_Pelanggan ?? 0;

                                $grandTotalPendaftar += $pendaftar;
                                $grandTotalPasang    += $pasang;
                                $grandTotalMutasi    += $mutasi;

                                $progress = $pendaftar > 0 ? "$pasang/$pendaftar" : '0/0';
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>Zona {{ romawi($row->zona) }}</td>
                                <td class="right progress">{{ $progress }}</td>
                                <td class="right">{{ number_format($pendaftar,0,',','.') }}</td>
                                <td class="right">{{ number_format($pasang,0,',','.') }}</td>
                                <td class="right">{{ number_format($mutasi,0,',','.') }}</td>
                            </tr>
                        @endforeach

                        @php
                            $totalProgress = $grandTotalPendaftar > 0 ? "$grandTotalPasang/$grandTotalPendaftar" : '0/0';
                        @endphp
                        <tr class="total-row">
                            <td>-</td>
                            <td>Total</td>
                            <td class="right">{{ $totalProgress }}</td>
                            <td class="right">{{ number_format($grandTotalPendaftar, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($grandTotalPasang, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($grandTotalMutasi, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>