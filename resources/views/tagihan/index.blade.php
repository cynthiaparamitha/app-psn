<!DOCTYPE html>
<html>
<head>
    <title>Tagihan PSN</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #fafafa;
        margin: 20px;
    }

    h2 {
        margin-bottom: 15px;
        color: #34495e;
        font-size: 24px;
        font-weight: bold;
    }

    /* FILTER BOX */
    .filter-box {
        background: #ffffff;
        padding: 12px 15px;
        border-radius: 6px;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: inline-block;
    }

    select, input[type=text] {
        padding: 6px 10px;
        margin-right: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    button {
        padding: 7px 18px;
        background: #3498db;
        border: none;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background: #2980b9;
    }

    .reset-text {
        margin-right: 10px;
        color: #888;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        font-weight: bold;
    }

    .reset-text:hover {
        text-decoration: underline;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-top: 10px;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }

    table th {
        background: #34495e;
        color: white;
        padding: 10px;
        font-weight: bold;
        border: 1px solid #ddd;
        text-align: center;
    }

    table td {
        padding: 8px 10px;
        border: 1px solid #ddd;
    }

    table tr:nth-child(even) {
        background: #f7f7f7;
    }

    table tr:hover {
        background: #e8f4ff;
    }

    .num { text-align: right; }
    .center { text-align: center; }

    .pagination {
        display: flex;
        list-style: none;
        padding-left: 0;
        margin-top: 20px;
    }

    .pagination li {
        margin-right: 8px;
    }

    .pagination li a,
    .pagination li span {
        padding: 6px 12px;
        background: #3498db;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
    }

    .pagination li.disabled span {
        background: #bdc3c7;
    }

    .pagination li a:hover {
        background: #2980b9;
    }
</style>
</head>

<body>

@include('layouts.navbar')

@php
    $total_bulan_drd       = $data->sum('jumlah_bulan_drd');
    $total_nominal_drd     = $data->sum('nominal_drd');

    $total_bulan_bayar     = $data->sum('jumlah_bulan_bayar');
    $total_nominal_bayar   = $data->sum('nominal_ar');

    $total_bulan_tunggakan = $data->sum('jumlah_bulan_menunggak');
    $total_nominal_tunggak = $data->sum('nominal_tunggakan');
@endphp

<h2>Tagihan PSN</h2>

<div class="filter-box">
<form method="GET" action="">

    <a href="{{ url('tagihan') }}" class="reset-text">⟳ Reset</a>

    <input type="text" name="search" placeholder="Cari nopel / nama"
           value="{{ $search ?? '' }}">

    <select name="zona">
        <option value="">-- Zona --</option>
        @foreach($zonaList as $z)
            <option value="{{ $z }}" {{ ($zona ?? '') == $z ? 'selected' : '' }}>
                {{ $z }}
            </option>
        @endforeach
    </select>

    <select name="cabang">
        <option value="">-- K. Pelayanan --</option>
        @foreach($cabangList as $c)
            <option value="{{ $c }}" {{ ($cabang ?? '') == $c ? 'selected' : '' }}>
                {{ $c }}
            </option>
        @endforeach
    </select>

    <select name="status">
        <option value="">-- Status --</option>
        @foreach($statusList as $s)
            <option value="{{ $s }}" {{ ($status ?? '') == $s ? 'selected' : '' }}>
                {{ $s }}
            </option>
        @endforeach
    </select>

    <select name="tunggakan">
        <option value="">-- Tunggakan --</option>
        <option value="1" {{ ($tunggakan ?? '') == '1' ? 'selected' : '' }}>
            Ada Tunggakan
        </option>
    </select>

    <select name="perPage">
        <option value="10"  {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
        <option value="25"  {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
        <option value="50"  {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
        <option value="all" {{ ($perPage ?? 10) == 'all' ? 'selected' : '' }}>All</option>
    </select>

    <button type="submit">Filter</button>

</form>
</div>

<table>
    <tr>
        <th rowspan="2">No Pel</th>
        <th rowspan="2">Nama</th>
        <th rowspan="2">Alamat</th>
        <th rowspan="2">Status</th>
        <th rowspan="2">K. Pelayanan</th>
        <th rowspan="2">Zona</th>

        <th colspan="2">DRD</th>
        <th colspan="2">PEMBAYARAN</th>
        <th colspan="2">TUNGGAKAN</th>
    </tr>

    <tr>
        <th>Bulan</th>
        <th>Tagihan</th>
        <th>Bulan</th>
        <th>Tagihan</th>
        <th>Bulan</th>
        <th>Tagihan</th>
    </tr>

    @foreach($data as $d)
    <tr>
        <td>{{ $d->nopel }}</td>
        <td>{{ $d->nama }}</td>
        <td>{{ $d->alamat }}</td>
        <td>{{ $d->status }}</td>
        <td>{{ $d->cabang }}</td>
        <td>{{ $d->zona }}</td>

        <td class="center">{{ $d->jumlah_bulan_drd }}</td>
        <td class="num">{{ number_format($d->nominal_drd, 0, ',', '.') }}</td>

        <td class="center">{{ $d->jumlah_bulan_bayar }}</td>
        <td class="num">{{ number_format($d->nominal_ar, 0, ',', '.') }}</td>

        <td class="center">{{ $d->jumlah_bulan_menunggak }}</td>
        <td class="num">{{ number_format($d->nominal_tunggakan, 0, ',', '.') }}</td>
    </tr>
    @endforeach

<tr style="background:#e8f2ff; font-weight:bold;">
    <td colspan="6" class="num">TOTAL :</td>

    <td class="center">{{ $totals['bulan_drd'] }}</td>
    <td class="num">{{ number_format($totals['nominal_drd'], 0, ',', '.') }}</td>

    <td class="center">{{ $totals['bulan_bayar'] }}</td>
    <td class="num">{{ number_format($totals['nominal_bayar'], 0, ',', '.') }}</td>

    <td class="center">{{ $totals['bulan_tunggakan'] }}</td>
    <td class="num">{{ number_format($totals['nominal_tunggak'], 0, ',', '.') }}</td>
</tr>
</table>

@if($perPage === 'all')
<div style="margin-top: 20px; font-size: 14px; color: #555;">
    Showing all {{ count($data) }} data
</div>
@endif

@if($perPage !== 'all')
<div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">

    <div style="font-size: 14px; color: #555;">
        Showing {{ $data->firstItem() }} - {{ $data->lastItem() }} 
        of {{ $data->total() }} data
    </div>

    <div style="display:flex; gap:8px; align-items:center;">

    {{ $data->onEachSide(0)->links('pagination::simple-default') }}

</div>

</div>
@endif

</body>
</html>