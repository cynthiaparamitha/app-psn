<!DOCTYPE html>
<html>
<head>
    <title>LHK PSN</title>

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

<h2>Laporan Harian Kas - Zona {{ $zona }}</h2>

<div class="filter-box">
    <form method="GET">
        <input type="hidden" name="zona" value="{{ $zona }}">

        <label><b>Periode:</b></label>
        <select name="tabul" onchange="this.form.submit()">

            @php
                $bulan = [
                    '01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
                    '05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Ags',
                    '09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
                ];
            @endphp

            @foreach($listTabul as $t)
                @php
                    $yr = substr($t->tabul,0,4);
                    $mo = substr($t->tabul,4,2);
                @endphp

                <option value="{{ $t->tabul }}" {{ $tabul==$t->tabul?'selected':'' }}>
                    {{ $bulan[$mo] }} {{ $yr }}
                </option>
            @endforeach

        </select>
    </form>
</div>

<a href="/lhk?tabul={{ $tabul }}" class="back-btn">← Kembali ke Zona</a>

<table>
<tr>
    <th>K. Pelayanan</th>
    <th>Air</th>
    <th>Administrasi</th>
    <th>Denda</th>
    <th>NAL</th>
    <th>Total</th>
</tr>

@php
    $totalAir=0; $totalAdm=0; $totalDenda=0; $totalNAL=0; $totalAll=0;
@endphp

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
    <td class="right">{{ number_format($row->air,0,',','.') }}</td>
    <td class="right">{{ number_format($row->administrasi,0,',','.') }}</td>
    <td class="right">{{ number_format($row->denda,0,',','.') }}</td>
    <td class="right">{{ number_format($row->NAL,0,',','.') }}</td>
    <td class="right">{{ number_format($row->total,0,',','.') }}</td>
</tr>

@endforeach

<tr class="total-row">
    <td>Total</td>
    <td class="right">{{ number_format($totalAir,0,',','.') }}</td>
    <td class="right">{{ number_format($totalAdm,0,',','.') }}</td>
    <td class="right">{{ number_format($totalDenda,0,',','.') }}</td>
    <td class="right">{{ number_format($totalNAL,0,',','.') }}</td>
    <td class="right">{{ number_format($totalAll,0,',','.') }}</td>
</tr>

</table>

</body>
</html>