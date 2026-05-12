<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan PSN</title>

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
            flex-wrap: wrap;
            gap: 10px;
        }

        select, input[type=text] {
            padding: 8px 12px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
            box-sizing: border-box;
        }

        input[type=text] {
            min-width: 200px;
        }

        button {
            padding: 8px 18px;
            background: #3498db;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        button:hover {
            background: #2980b9;
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

        table th a {
            color: white;
            text-decoration: none;
            display: inline-block;
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

        .sort-icon {
            margin-left: 4px;
            font-size: 12px;
            opacity: .8;
        }

        .rekap-btn {
            padding: 8px 14px;
            background: #27ae60;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .rekap-btn:hover {
            background: #239b56;
        }

        .reset-text {
            color: #888;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .reset-text:hover {
            text-decoration: underline;
        }

        .pagination-container {
            margin-top: 20px; 
            display: flex; 
            flex-direction: column;
            gap: 10px;
            justify-content: space-between; 
            align-items: center;
        }

        .pagination-container nav {
            display: inline-block;
        }

        .pagination-container ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-container li {
            margin: 0 4px;
        }

        .pagination-container li a,
        .pagination-container li span {
            display: inline-block;
            padding: 6px 14px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 13px;
            transition: background 0.2s ease;
        }

        .pagination-container li a:hover {
            background: #2980b9;
        }

        .pagination-container li[aria-disabled="true"] span,
        .pagination-container li.disabled span {
            background: #bdc3c7;
            color: #ffffff;
            cursor: not-allowed;
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

            .pagination-container {
                flex-direction: row;
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

    <h2>Detail Pelanggan</h2>

    <div class="filter-box">
        <form method="GET">

            <a href="{{ route('pelanggan.index') }}" class="reset-text">⟳ Reset</a>

            <input type="text" name="search" placeholder="Cari nopel / nama" value="{{ $search ?? '' }}">

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
                <option value="">-- K. Pelayanan --</option>
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

            <select name="perPage">
                <option value="10"  {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25"  {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50"  {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                <option value="all" {{ ($perPage ?? 10) == 'all' ? 'selected' : '' }}>All</option>
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
            $sortIcons[$col] = ($sortCol === $col)
                ? ($sortOrder === 'asc' ? '▲' : '▼')
                : '';
        }
    @endphp

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
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
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            </div>

            @if($perPage === 'all')
                <div style="margin-top: 20px; font-size: 14px; color: #555;">
                    Showing all {{ count($data) }} data
                </div>
            @else
                <div class="pagination-container">
                    <div style="font-size: 14px; color: #555;">
                        Showing {{ $data->firstItem() }} - {{ $data->lastItem() }} of {{ $data->total() }} data
                    </div>
                    <div>
                        {{ $data->onEachSide(0)->links('pagination::simple-default') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

</body>
</html>