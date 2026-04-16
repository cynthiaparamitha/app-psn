<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemakaian PSN - Zona</title>

    <style>
        body { font-family: Arial; background: #f4f6f9; margin: 20px; }
        h2 { margin-bottom: 18px; color: #2c3e50; font-size: 26px; font-weight: bold; }

        .filter-box {
            background: #fff; padding: 12px 15px; border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;
            display: inline-block;
        }

        table {
            width: 100%; border-collapse: collapse; background: white;
            border-radius: 6px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        table th {
            background: #34495e; color: white; padding: 10px 8px;
            font-size: 14px; font-weight: bold; border: 1px solid #ddd; text-align: center;
        }

        table td {
            padding: 9px 8px; border: 1px solid #ddd; font-size: 14px;
        }

        .right { text-align: right; font-variant-numeric: tabular-nums; }

        .total-row {
            background: #27ae60 !important;
            color: white; font-weight: bold;
        }
        select {
            padding: 7px 10px;
            margin-left: 8px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<h2>Laporan Pemakaian PSN</h2>

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
                    $tahun = substr($t->tabul,0,4);
                    $bulan = substr($t->tabul,4,2);
                    $label = $bulanSingkat[$bulan].' '.$tahun;
                @endphp

                <option value="{{ $t->tabul }}" {{ $tabul==$t->tabul?'selected':'' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<table>
<tr>
    <th rowspan="2">Zona</th>

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

@php
$t = [
    'plg0'=>0,'m30'=>0,'tag0'=>0,
    'plg1'=>0,'m31'=>0,'tag1'=>0,
    'plg6'=>0,'m36'=>0,'tag6'=>0,
    'plg11'=>0,'m311'=>0,'tag11'=>0,
    'plg21'=>0,'m321'=>0,'tag21'=>0,
];
@endphp

@foreach($data as $row)

@php
$t['plg0'] += $row->jumlah_plg_k_0;
$t['m30']  += $row->kubik_0;
$t['tag0'] += $row->tagihan_k_0;

$t['plg1'] += $row->jumlah_plg_k_1_5;
$t['m31']  += $row->kubik_1_5;
$t['tag1'] += $row->tagihan_k_1_5;

$t['plg6'] += $row->jumlah_plg_k_6_10;
$t['m36']  += $row->kubik_6_10;
$t['tag6'] += $row->tagihan_k_6_10;

$t['plg11'] += $row->jumlah_plg_k_11_20;
$t['m311']  += $row->kubik_11_20;
$t['tag11'] += $row->tagihan_k_11_20;

$t['plg21'] += $row->jumlah_plg_lbh_20;
$t['m321']  += $row->kubik_lbh_20;
$t['tag21'] += $row->tagihan_lbh_20;
@endphp

<tr>
    <td>{{ $row->zona_cd }}</td>

    <td class="right">{{ number_format($row->jumlah_plg_k_0, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->kubik_0, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->tagihan_k_0, 0, ',', '.') }}</td>

    <td class="right">{{ number_format($row->jumlah_plg_k_1_5, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->kubik_1_5, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->tagihan_k_1_5, 0, ',', '.') }}</td>

    <td class="right">{{ number_format($row->jumlah_plg_k_6_10, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->kubik_6_10, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->tagihan_k_6_10, 0, ',', '.') }}</td>

    <td class="right">{{ number_format($row->jumlah_plg_k_11_20, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->kubik_11_20, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->tagihan_k_11_20, 0, ',', '.') }}</td>

    <td class="right">{{ number_format($row->jumlah_plg_lbh_20, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->kubik_lbh_20, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->tagihan_lbh_20, 0, ',', '.') }}</td>
</tr>
@endforeach

<tr class="total-row">
    <td>Total</td>

    <td class="right">{{ number_format($t['plg0'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['m30'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['tag0'], 0, ',', '.') }}</td>

    <td class="right">{{ number_format($t['plg1'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['m31'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['tag1'], 0, ',', '.') }}</td>

    <td class="right">{{ number_format($t['plg6'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['m36'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['tag6'], 0, ',', '.') }}</td>

    <td class="right">{{ number_format($t['plg11'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['m311'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['tag11'], 0, ',', '.') }}</td>

    <td class="right">{{ number_format($t['plg21'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['m321'], 0, ',', '.') }}</td>
    <td class="right">{{ number_format($t['tag21'], 0, ',', '.') }}</td>
</tr>

</table>

</body>
</html>