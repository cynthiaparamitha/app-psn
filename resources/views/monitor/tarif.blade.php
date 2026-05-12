<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Tarif PSN</title>

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

        select {
            padding: 8px 12px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
            box-sizing: border-box;
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
            background: #eef5ff !important;
        }

        table tr.warning {
            background: #fdf2f2;
        }
        table tr.warning td {
            color: #c0392b;
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

    <h2>📊 Monitoring Tarif</h2>

    <div class="filter-box">
        <form method="GET">

            <a href="{{ url()->current() }}" class="reset-text">⟳ Reset</a>

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

        </form>
    </div>

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
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
                                <td style="text-align: center;">{{ $d->Plg_CD }}</td>
                                <td style="text-align: center;">{{ $d->Nopel }}</td>
                                <td>{{ $d->Nama }}</td>
                                <td style="text-align: center;">{{ $d->Tarif }}</td>
                                <td style="text-align: center;"><b>{{ $d->bulan }}</b></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:15px; color: #888;">
                                    <i>Tidak ada data ditemukan...</i>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(request('perPage') === 'all')
                <div style="margin-top: 20px; font-size: 14px; color: #555;">
                    Showing all {{ count($data) }} data
                </div>
            @else
                @if(method_exists($data, 'links'))
                    <div class="pagination-container">
                        <div style="font-size: 14px; color: #555;">
                            Showing {{ $data->firstItem() }} - {{ $data->lastItem() }} of {{ $data->total() }} data
                        </div>
                        <div>
                            {{ $data->onEachSide(0)->links('pagination::simple-default') }}
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>

</div>

</body>
</html>