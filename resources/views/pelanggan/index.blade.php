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
        opacity: .8;
    }

    .rekap-btn {
        padding: 8px 14px;
        background: #27ae60;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        margin-left: 10px;
    }

    .rekap-btn:hover {
        background: #239b56;
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
</style>
</head>

<body>

@include('layouts.navbar')

<h2>Detail Pelanggan PSN</h2>

<div class="filter-box">
<form method="GET">

    <a href="{{ route('pelanggan.index') }}" class="reset-text">⟳ Reset</a>

    <input type="text" name="search" placeholder="Cari nopel / nama"
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
        📊 Rekap
    </a>

</form>
</div>

@php
    $sortCol   = $sort ?? '';
    $sortOrder = $order ?? 'asc';

    $nextAsc  = 'asc';
    $nextDesc = 'desc';

    $columns = ['nopel', 'nama'];

    $sortUrls = [];
    $sortIcons = [];

    foreach ($columns as $col) {

        $nextOrder = ($sortCol === $col && $sortOrder === 'asc') ? 'desc' : 'asc';

        $query = array_merge(request()->query(), [
            'sort'  => $col,
            'order' => $nextOrder,
        ]);

        $sortUrls[$col] = url()->current() . '?' . http_build_query($query);

        if ($sortCol === $col) {
            $sortIcons[$col] = $sortOrder === 'asc' ? '▲' : '▼';
        } else {
            $sortIcons[$col] = '';
        }
    }
@endphp

<table>
<tr>
    <th>
    <a href="{{ $sortUrls['nopel'] }}">
        No Pel <span class="sort-icon">{{ $sortIcons['nopel'] }}</span>
    </a>
    </th>

    <th>
        <a href="{{ $sortUrls['nama'] }}">
            Nama <span class="sort-icon">{{ $sortIcons['nama'] }}</span>
        </a>
    </th>

    <th>Alamat</th>
    <th>Tarif</th>
    <th>No Meter</th>
    <th>Cabang</th>
    <th>Zona</th>
    <th>Status</th>
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