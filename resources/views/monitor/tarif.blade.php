<!DOCTYPE html>
<html>
<head>
    <title>Monitoring Tarif PSN</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #fafafa;
        margin: 20px;
    }

    h2 {
        margin-bottom: 15px;
        font-size: 24px;
        color: #34495e;
        font-weight: bold;
    }

    .filter-box {
        background: #ffffff;
        padding: 15px;
        border-radius: 6px;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: inline-block;
    }

    select {
        padding: 7px 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        margin-right: 10px;
    }

    .btn-reset {
        color: #888;
        font-size: 14px;
        text-decoration: none;
        font-weight: bold;
        margin-left: 10px;
    }

    .btn-reset:hover {
        text-decoration: underline;
        color: #333;
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

    .warning {
        background: #ffdddd !important;
    }
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

<h2>Monitoring Tarif PSN</h2>

<div class="filter-box">
    <form method="GET">

        <select name="bulan" onchange="this.form.submit()">
            <option value="">Semua Data</option>
            <option value="1" {{ request('bulan')==1 ? 'selected' : '' }}>1 Bulan</option>
            <option value="2" {{ request('bulan')==2 ? 'selected' : '' }}>2 Bulan</option>
            <option value="3" {{ request('bulan')==3 ? 'selected' : '' }}>3 Bulan</option>
            <option value="4" {{ request('bulan')==4 ? 'selected' : '' }}>4 Bulan</option>
            <option value="5" {{ request('bulan')==5 ? 'selected' : '' }}>5 Bulan</option>
            <option value="6" {{ request('bulan')==6 ? 'selected' : '' }}>≥ 6 Bulan</option>
        </select>

        <select name="sort" onchange="this.form.submit()">
            <option value="bulan" {{ request('sort')=='bulan' ? 'selected' : '' }}>Sort by Bulan</option>
        </select>

        <select name="order" onchange="this.form.submit()">
            <option value="desc" {{ request('order')=='desc' ? 'selected' : '' }}>Bulan Terbanyak</option>
            <option value="asc"  {{ request('order')=='asc'  ? 'selected' : '' }}>Bulan Tersedikit</option>
        </select>

        <select name="perPage" onchange="this.form.submit()">
            <option value="10"  {{ request('perPage')==10 ? 'selected' : '' }}>10</option>
            <option value="25"  {{ request('perPage')==25 ? 'selected' : '' }}>25</option>
            <option value="50"  {{ request('perPage')==50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage')==100 ? 'selected' : '' }}>100</option>
            <option value="all" {{ request('perPage')=='all' ? 'selected' : '' }}>All</option>
        </select>

        <a href="{{ url()->current() }}" class="btn-reset">⟳ Reset</a>

    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Kode Pelanggan</th>
            <th>No Pelanggan</th>
            <th>Nama</th>
            <th>Tarif</th>
            <th>Lama PSN (Bulan)</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($data as $d)
        <tr class="{{ $d->bulan >= 6 ? 'warning' : '' }}">
            <td>{{ $d->Plg_CD }}</td>
            <td>{{ $d->Nopel }}</td>
            <td>{{ $d->Nama }}</td>
            <td>{{ $d->Tarif }}</td>
            <td><b>{{ $d->bulan }}</b></td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align:center; padding:15px;">
                <i>Tidak ada data ditemukan...</i>
            </td>
        </tr>
        @endforelse

    </tbody>
</table>

@if(request('perPage') !== 'all' && method_exists($data, 'links'))
    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">

        <div style="font-size: 14px; color: #555;">
            Showing {{ $data->firstItem() }} - {{ $data->lastItem() }}
            of {{ $data->total() }} data
        </div>

        <div>
            {{ $data->onEachSide(0)->links('pagination::simple-default') }}
        </div>

    </div>
@endif

@if(request('perPage') === 'all')
    <div style="margin-top: 20px; font-size: 14px; color: #555;">
        Showing all {{ count($data) }} data
    </div>
@endif

</body>
</html>