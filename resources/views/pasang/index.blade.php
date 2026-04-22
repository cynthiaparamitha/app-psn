<!DOCTYPE html>
<html>
<head>
    <title>Progress Pemasangan Zona</title>

    <style>
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

        .progress {
            font-weight: bold;
            color: #2c3e50;
        }

    </style>
</head>
<body>

@include('layouts.navbar')

<h2>Progress Pemasangan Per Zona</h2>

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
                    $th = substr($t->tabul, 0, 4);
                    $bl = substr($t->tabul, 4, 2);
                @endphp

                <option value="{{ $t->tabul }}" {{ $tabul == $t->tabul ? 'selected' : '' }}>
                    {{ $bulanSingkat[$bl] }} {{ $th }}
                </option>
            @endforeach
        </select>
    </form>
</div>

@php
function romawi($angka) {
    $map = [
        1=>'I',2=>'II',3=>'III',4=>'IV'
    ];
    return $map[$angka] ?? $angka;
}
@endphp

<table>
<tr>
    <th>No</th>
    <th>Zona</th>
    <th>Progress Pasang</th>
    <th>Pendaftar</th>
    <th>Pasang</th>
    <th>Mutasi Pelanggan Baru</th>
</tr>

@foreach($data as $i => $row)

@php
$pendaftar = $row->Pendaftar ?? 0;
$pasang    = $row->Terpasang ?? 0;
$mutasi    = $row->Mutasi_Pelanggan ?? 0;

$progress = $pendaftar > 0 ? "$pasang/$pendaftar" : '0/0';
@endphp

<tr>
    <td class="left">{{ $i + 1 }}</td>
    <td>Zona {{ romawi($row->zona) }}</td>
    <td class="right progress">{{ $progress }}</td>
    <td class="right">{{ number_format($pendaftar,0,',','.') }}</td>
    <td class="right">{{ number_format($pasang,0,',','.') }}</td>
    <td class="right">{{ number_format($mutasi,0,',','.') }}</td>
</tr>

@endforeach

</table>

</body>
</html>