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
</style>
</head>

<body>

@include('layouts.navbar')

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
        <option value="">-- Cabang --</option>
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

    <button type="submit">Filter</button>

</form>
</div>

<table>
    <tr>
        <th rowspan="2">No Pel</th>
        <th rowspan="2">Nama</th>
        <th rowspan="2">Alamat</th>
        <th rowspan="2">Status</th>
        <th rowspan="2">Cabang</th>
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
</table>

</body>
</html>