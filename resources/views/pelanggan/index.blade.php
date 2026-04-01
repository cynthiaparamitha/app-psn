<!DOCTYPE html>
<html>
<head>
    <title>Detail Pelanggan</title>

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
        border: 1px solid #ccc;
        border-radius: 4px;
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
        cursor: pointer;
        user-select: none;
    }

    table th a {
        color: white;
        text-decoration: none;
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

    .sort-icon {
        margin-left: 4px;
        font-size: 12px;
        opacity: 0.8;
    }

    .rekap-btn {
    padding: 8px 16px;
    background: #27ae60;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    margin-left: 10px;
    font-weight: bold;
    display: inline-block;
    }
    .rekap-btn:hover {
        background: #239b56;
    }

    .reset-text {
        display: inline-block;
        margin-bottom: 8px;
        margin-right: 8px;
        color: #adadad;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    .reset-text:hover {
        text-decoration: underline;
    }

</style>

</head>
<body>

@include('layouts.navbar')

<h2>Detail Pelanggan PSN</h2>

<div class="filter-box">
<form method="GET">

    <a href="{{ route('pelanggan.index') }}" class="reset-text">
        ⟳ Reset
    </a>

    <input type="text" 
           name="search" 
           placeholder="Cari nopel / nama"
           value="{{ $search ?? '' }}">

    <select name="tarif">
        <option value="">-- Tarif --</option>
        @foreach($tarifList as $t)
            @php $tTrim = trim($t); @endphp
            <option value="{{ $tTrim }}" {{ ($tarif ?? '') == $tTrim ? 'selected' : '' }}>
                {{ $tTrim }}
            </option>
        @endforeach
    </select>
    
    <!-- <select name="tarif">
        <option value="">-- Tarif --</option>
        @foreach($tarifList as $t)
            <option value="{{ $t }}" {{ ($tarif ?? '') == $t ? 'selected' : '' }}>
                {{ $t }}
            </option>
        @endforeach
    </select> -->

    <select name="cabang">
        <option value="">-- Cabang --</option>
        @foreach($cabangList as $c)
            <option value="{{ $c }}" {{ ($cabang ?? '') == $c ? 'selected' : '' }}>
                {{ $c }}
            </option>
        @endforeach
    </select>

    <select name="zona">
        <option value="">-- Zona --</option>
        @foreach($zonaList as $z)
            <option value="{{ $z }}" {{ ($zona ?? '') == $z ? 'selected' : '' }}>
                {{ $z }}
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

    <button type="submit">Filter</button>
    
    <a href="{{ route('pelanggan.rekap', request()->query()) }}" class="rekap-btn">
        📊 Rekapan
    </a>

</form>
</div>

@php
    function sortUrl($col, $nextOrder) {
        return "?sort=$col&order=$nextOrder&" . http_build_query(request()->except('sort','order'));
    }

    function sortIcon($col, $sort, $order) {
        if ($col !== $sort) return '';
        return $order === 'asc' ? '▲' : '▼';
    }
@endphp

<table>
<tr>
    <th><a href="{{ sortUrl('nopel', $nextOrder) }}">No Pel <span class="sort-icon">{{ sortIcon('nopel', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('nama', $nextOrder) }}">Nama <span class="sort-icon">{{ sortIcon('nama', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('alamat', $nextOrder) }}">Alamat <span class="sort-icon">{{ sortIcon('alamat', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('kode_tarif', $nextOrder) }}">Tarif <span class="sort-icon">{{ sortIcon('kode_tarif', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('no_meter', $nextOrder) }}">No Meter <span class="sort-icon">{{ sortIcon('no_meter', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('cabang', $nextOrder) }}">Cabang <span class="sort-icon">{{ sortIcon('cabang', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('zona', $nextOrder) }}">Zona <span class="sort-icon">{{ sortIcon('zona', $sort, $order) }}</span></a></th>
    <th><a href="{{ sortUrl('status', $nextOrder) }}">Status <span class="sort-icon">{{ sortIcon('status', $sort, $order) }}</span></a></th>
</tr>

@foreach($data as $row)
<tr>
    <td>{{ $row->nopel }}</td>
    <td>{{ $row->nama }}</td>
    <td>{{ $row->alamat }}</td>
    <td>{{ $row->kode_tarif }}</td>
    <td>{{ $row->no_meter }}</td>
    <td>{{ $row->cabang }}</td>
    <td>{{ $row->zona }}</td>
    <td>{{ $row->status }}</td>
</tr>
@endforeach

</table>

</body>
</html>