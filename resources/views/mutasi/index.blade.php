<!DOCTYPE html>
<html>
<head>
    <title>Laporan Mutasi PSN</title>

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
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    }

    table th {
        background: #34495e;
        color: white;
        padding: 10px 6px;
        font-weight: bold;
        border: 1px solid #ddd;
        font-size: 13px;
        text-align: center;
    }

    table td {
        padding: 8px 6px;
        border: 1px solid #ddd;
        font-size: 13px;
    }

    table tr:nth-child(even) {
        background: #f9f9f9;
    }

    table tr:hover {
        background: #eef5ff;
    }

    .right {
        text-align: right;
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

<h2>Laporan Mutasi</h2>

<div class="filter-box">
    <form method="GET">
        <label><b>Periode:</b></label>

        <select name="tabul" onchange="this.form.submit()">

            @php
                $bulanSingkat = [
                    '01' => 'Jan',
                    '02' => 'Feb',
                    '03' => 'Mar',
                    '04' => 'Apr',
                    '05' => 'Mei',
                    '06' => 'Jun',
                    '07' => 'Jul',
                    '08' => 'Ags',
                    '09' => 'Sep',
                    '10' => 'Okt',
                    '11' => 'Nov',
                    '12' => 'Des',
                ];
            @endphp

            @foreach($listTabul as $t)

                @php
                    $tahun = substr($t->tabul, 0, 4);
                    $bulan = substr($t->tabul, 4, 2);
                    $label = $bulanSingkat[$bulan] . ' ' . $tahun;
                @endphp

                <option value="{{ $t->tabul }}" {{ $tabul == $t->tabul ? 'selected' : '' }}>
                    {{ $label }}
                </option>

            @endforeach
        </select>
    </form>
</div>

<table>
<tr>
    <th>K. Pelayanan</th>
    <th>Golongan</th>
    <th>Alamat Plg</th>
    <th>Ganti Meter</th>
    <th>PK</th>
    <th>Plg Baru</th>
    <th>Aktif ke Nonaktif</th>
    <th>Nama Plg</th>
    <th>Stand meter</th>
    <th>Ganti Nopel</th>
    <th>No. HP</th>
</tr>

@foreach($data as $row)
<tr>
    <td>{{ $row->cabang }}</td>
    <td class="right">{{ $row->golongan }}</td>
    <td class="right">{{ $row->alamat_pelanggan }}</td>
    <td class="right">{{ $row->ganti_meter }}</td>
    <td class="right">{{ $row->pengaktifan_kembali }}</td>
    <td class="right">{{ $row->pelanggan_baru }}</td>
    <td class="right">{{ $row->aktif_ke_nonaktif }}</td>
    <td class="right">{{ $row->nama_pelanggan }}</td>
    <td class="right">{{ $row->stand_meter }}</td>
    <td class="right">{{ $row->ganti_nopel }}</td>
    <td class="right">{{ $row->no_handphone }}</td>
</tr>
@endforeach

<tr class="total-row">
    <td>Total</td>
    <td class="right">{{ $totals['golongan'] }}</td>
    <td class="right">{{ $totals['alamat_pelanggan'] }}</td>
    <td class="right">{{ $totals['ganti_meter'] }}</td>
    <td class="right">{{ $totals['pengaktifan_kembali'] }}</td>
    <td class="right">{{ $totals['pelanggan_baru'] }}</td>
    <td class="right">{{ $totals['aktif_ke_nonaktif'] }}</td>
    <td class="right">{{ $totals['nama_pelanggan'] }}</td>
    <td class="right">{{ $totals['stand_meter'] }}</td>
    <td class="right">{{ $totals['ganti_nopel'] }}</td>
    <td class="right">{{ $totals['no_handphone'] }}</td>
</tr>

</table>

</body>
</html>