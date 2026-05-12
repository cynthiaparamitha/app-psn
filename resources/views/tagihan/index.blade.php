<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tagihan PSN</title>

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

        .num { text-align: right; }
        .center { text-align: center; }

        table tr.warning-row {
            background: #fff9e6;
        }
        table tr.warning-row td {
            color: #b7791f;
        }

        table tr.danger-row {
            background: #fdf2f2;
        }
        table tr.danger-row td {
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

@php
    $total_bulan_drd       = $fullTotal['bulan_drd'];
    $total_nominal_drd     = $fullTotal['nom_drd'];

    $total_bulan_bayar     = $fullTotal['bulan_bayar'];
    $total_nominal_bayar   = $fullTotal['nom_bayar'];

    $total_bulan_tunggakan = $fullTotal['bulan_tunggak'];
    $total_nominal_tunggak = $fullTotal['nom_tunggak'];
@endphp

<div class="container-drd">

    <h2>📊 Laporan Tagihan</h2>

    <div class="filter-box">
        <form method="GET" action="">

            <a href="{{ url('tagihan') }}" class="reset-text">⟳ Reset</a>

            <input type="text" name="search" placeholder="Cari nopel / nama" value="{{ $search ?? '' }}">

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
                <option value="1" {{ ($tunggakan ?? '') == '1' ? 'selected' : '' }}>Ada Tunggakan</option>
                <option value="0" {{ ($tunggakan ?? '') == '0' ? 'selected' : '' }}>Tidak Ada Tunggakan</option>
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

    @php
    function sort_link($label, $field, $sort) {
        $currentSort = request('sort');
        $direction = 'desc';

        if ($currentSort === $field . '_desc') {
            $direction = 'asc';
        }

        $newSort = $field . '_' . $direction;

        $icon = '';
        if ($currentSort === $field . '_asc') {
            $icon = ' ▲';
        } elseif ($currentSort === $field . '_desc') {
            $icon = ' ▼';
        }

        $query = request()->query();
        $query['sort'] = $newSort;

        return '<a href="?' . http_build_query($query) . '" style="color:white; text-decoration:none; display:inline-block;">'
            . $label . '<span style="font-size: 11px; opacity: 0.8;">' . $icon . '</span></a>';
    }
    @endphp

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">{!! sort_link('No Pel', 'nopel', $sort) !!}</th>
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
                            <th>{!! sort_link('Bulan', 'drd', $sort) !!}</th>
                            <th>Tagihan</th>
                            <th>{!! sort_link('Bulan', 'bayar', $sort) !!}</th>
                            <th>Tagihan</th>
                            <th>{!! sort_link('Bulan', 'tunggak', $sort) !!}</th>
                            <th>Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            @php
                                $rowClass = '';
                                if ($d->tunggakan_bulan >= 6) {
                                    $rowClass = 'danger-row';
                                } elseif ($d->tunggakan_bulan >= 3) {
                                    $rowClass = 'warning-row';
                                }
                            @endphp

                            <tr class="{{ $rowClass }}">
                                <td class="center">{{ $d->nopel }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->alamat }}</td>
                                <td class="center">{{ $d->status }}</td>
                                <td class="center">{{ $d->cabang }}</td>
                                <td class="center">{{ $d->zona }}</td>

                                <td class="center">{{ $d->drd_bulan }}</td>
                                <td class="num">{{ number_format($d->drd_tagihan, 0, ',', '.') }}</td>

                                <td class="center">{{ $d->pembayaran_bulan }}</td>
                                <td class="num">{{ number_format($d->pembayaran_tagihan, 0, ',', '.') }}</td>

                                <td class="center"><b>{{ $d->tunggakan_bulan }}</b></td>
                                <td class="num"><b>{{ number_format($d->tunggakan_tagihan, 0, ',', '.') }}</b></td>
                            </tr>
                        @endforeach

                        <tr style="background: #34495e; color: white; font-weight: bold; border-top: 2px solid #2c3e50; pointer-events: none;">
                            <td colspan="6" class="num" style="color: white;">TOTAL :</td>
                            <td class="center">{{ $total_bulan_drd }}</td>
                            <td class="num">{{ number_format($total_nominal_drd, 0, ',', '.') }}</td>
                            <td class="center">{{ $total_bulan_bayar }}</td>
                            <td class="num">{{ number_format($total_nominal_bayar, 0, ',', '.') }}</td>
                            <td class="center">{{ $total_bulan_tunggakan }}</td>
                            <td class="num">{{ number_format($total_nominal_tunggak, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($perPage === 'all')
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