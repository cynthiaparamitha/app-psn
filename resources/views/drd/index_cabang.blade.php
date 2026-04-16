<!DOCTYPE html>
<html>
<head>
    <title>Laporan DRD PSN</title>

<style>
    .back-btn {
        display:inline-block;
        padding:8px 14px;
        background:#2980b9;
        color:white !important;
        text-decoration:none;
        border-radius:6px;
        margin-bottom:12px;
        margin-left: 12px;
        font-weight:bold;
        box-shadow:0 2px 6px rgba(0,0,0,0.15);
        transition:0.2s;
    }
    .back-btn:hover {
        background:#1f6aa5;
    }

    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        margin: 20px;
    }

    h2 {
        margin-bottom: 18px;
        color: #2c3e50;
        font-size: 26px;
        font-weight: bold;
    }

    .filter-box {
        background: #ffffff;
        padding: 12px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: inline-block;
    }

    select {
        padding: 7px 10px;
        margin-left: 8px;
        border: 1px solid #bbb;
        border-radius: 5px;
        font-size: 14px;
        background: #fff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    table th {
        background: #34495e;
        color: white;
        padding: 10px 8px;
        font-size: 14px;
        font-weight: bold;
        border: 1px solid #ddd;
        text-align: center;
    }

    table td {
        padding: 9px 8px;
        border: 1px solid #ddd;
        font-size: 14px;
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
</style>

</head>
<body>


@include('layouts.navbar')

<h2>Laporan DRD PSN Zona {{ $zona ?? '' }}</h2>

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

<table>
<tr>
    <th>K. Pelayanan</th>
    <th>Jumlah</th>
    <th>Kubik</th>
    <th>Nominal Air</th>
    <th>Administrasi</th>
    <th>Koreksi Air</th>
    <th>Total</th>
</tr>
<a href="/drd?tabul={{ $tabul }}" class="back-btn">← Kembali ke Zona</a>

@php
$totalJumlah = 0;
$totalKubikasi = 0;
$totalNominal = 0;
$totalAdministrasi = 0;
$totalKoreksi = 0;
$totalTotal = 0;
@endphp

@foreach($data as $row)

@php
$totalJumlah += $row->jumlah;
$totalKubikasi += $row->kubikasi;
$totalNominal += $row->nominal;
$totalAdministrasi += $row->administrasi;
$totalKoreksi += $row->koreksi;
$totalTotal += $row->total;
@endphp

<tr>
    <td>{{ $row->cabang }}</td>
    <td class="right">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->kubikasi, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->nominal, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->administrasi, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->koreksi, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($row->total, 0, ',', '.') }}</td>
</tr>

@endforeach

<tr class="total-row">
    <td>Total</td>
    <td class="right">{{ number_format($totalJumlah, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($totalKubikasi, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($totalNominal, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($totalAdministrasi, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($totalKoreksi, 0, ',', '.') }}</td>
    <td class="right">{{ number_format($totalTotal, 0, ',', '.') }}</td>
</tr>

</table>

</body>
</html>