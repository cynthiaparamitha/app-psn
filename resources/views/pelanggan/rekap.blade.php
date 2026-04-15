<!DOCTYPE html>
<html>
<head>
    <title>Rekap Pelanggan PSN</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    margin: 20px;
}

h2 {
    margin-bottom: 20px;
    font-size: 26px;
    color: #2c3e50;
}

.section-title {
    margin-bottom: 8px;
    font-size: 20px;
    color: #2c3e50;
    border-left: 4px solid #2980b9;
    padding-left: 8px;
    font-weight: bold;
}

.card {
    background: white;
    padding: 18px;
    margin-bottom: 22px;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border-radius: 6px;
    overflow: hidden;
    table-layout: fixed; /* Agar kolom rapi & sama lebar */
}

th {
    background: #34495e;
    color: white;
    padding: 10px;
    text-align: left;
    font-size: 14px;
}

td {
    padding: 8px 10px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    word-wrap: break-word;     
    white-space: normal;
}

tr:nth-child(even) {
    background: #f7f9fc;
}

.back-btn {
    display: inline-block;
    padding: 8px 14px;
    background: #2980b9;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 20px;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.back-btn:hover {
    background: #1f6fa3;
}

.total-box {
    font-size: 26px;
    font-weight: bold;
    color: #27ae60;
    text-align: center;
    padding: 10px;
}

.total-row {
    font-weight: bold;
    background: #f0f0f0;
}
</style>
</head>
<body>

@include('layouts.navbar')

<h2>📊 Rekapan Pelanggan PSN</h2>
<a href="{{ url()->previous() }}" class="back-btn">← Kembali</a>

<div class="card">
    <div class="section-title">Rekap Berdasarkan Tarif</div>
    <table>
        <tr>
            <th>Tarif</th>
            <th>Jumlah Pelanggan</th>
        </tr>
        @foreach($rekapTarif as $tarif => $jumlah)
            <tr>
                <td>{{ $tarif }}</td>
                <td>{{ $jumlah }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>Total</td>
            <td>{{ $rekapTarif->sum() }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <div class="section-title">Rekap Berdasarkan Kantor Pelayanan</div>
    <table>
        <tr>
            <th>Kantor Pelayanan</th>
            <th>Jumlah Pelanggan</th>
        </tr>
        @foreach($rekapCabang as $cabang => $jumlah)
            <tr>
                <td>{{ $cabang }}</td>
                <td>{{ $jumlah }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>Total</td>
            <td>{{ $rekapCabang->sum() }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <div class="section-title">Rekap Berdasarkan Zona</div>
    <table>
        <tr>
            <th>Zona</th>
            <th>Jumlah Pelanggan</th>
        </tr>
        @foreach($rekapZona as $zona => $jumlah)
            <tr>
                <td>{{ $zona }}</td>
                <td>{{ $jumlah }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>Total</td>
            <td>{{ $rekapZona->sum() }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <div class="section-title">Rekap Berdasarkan Status</div>
    <table>
        <tr>
            <th>Status</th>
            <th>Jumlah Pelanggan</th>
        </tr>
        @foreach($rekapStatus as $status => $jumlah)
            <tr>
                <td>{{ $status }}</td>
                <td>{{ $jumlah }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>Total</td>
            <td>{{ $rekapStatus->sum() }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <div class="section-title">Total Keseluruhan Pelanggan</div>
    <div class="total-box">{{ $total }}</div>
</div>

</body>
</html>