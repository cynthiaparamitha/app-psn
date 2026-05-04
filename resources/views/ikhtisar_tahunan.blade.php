<!DOCTYPE html>
<html>
<head>
    <title>Ikhtisar Tahunan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 20px;
        }

        .container-ikhtisar {
            max-width: 1360px;
            margin: 0;
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

        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .graph-table-row {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .graph-col {
            flex: 1 1 60%;
            min-width: 400px;
        }

        .graph-col-2 {
            flex: 1 1 100%;
            max-width: 100%;
            min-width: 400px;
        }

        .table-col {
            flex: 1 1 40%;
            min-width: 400px;
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

        .right { text-align: right; }

        @media (max-width: 1360px) {
            .graph-table-row {
                flex-direction: column;
            }

            .graph-col,
            .table-col {
                flex: 100%;
                max-width: 100%;
            }
        }

        @media(max-width: 900px) {
            .graph-table-row {
                flex-direction: column;
            }
            .graph-col, .table-col {
                flex: 100%;
            }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container-ikhtisar">

    <h2>Ikhtisar Tahunan</h2>

    <div class="filter-box">
        <form method="GET">
            <label><b>Tahun:</b></label>
            <select name="tahun" onchange="this.form.submit()">
                @foreach($listTahun as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="graph-table-row">

        <div class="card graph-col">
            <h3>DRD <?= $tahun ?></h3>
            <canvas id="grafikTahunan" height="150"></canvas>
        </div>

        <div class="card table-col">
            <h3>Air Terjual <?= $tahun ?></h3>

            <table>
                <tr>
                    <th>Bulan</th>
                    <th>Kubikasi (m³)</th>
                </tr>

                <?php foreach($labels as $i => $bulan): ?>
                <tr>
                    <td><?= $bulan ?></td>
                    <td class="right"><?= number_format($kubik[$i] ?? 0, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>

                <tr style="background:#27ae60;color:white;font-weight:bold;">
                    <td>Total</td>
                    <td class="right">
                        <?= number_format(array_sum($kubik), 0, ',', '.') ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="graph-table-row">
    <div class="card graph-col-2" style="margin-top:20px;">
        <h3>Grafik Pemakaian Pelanggan (Per Kubik) <?= $tahun ?></h3>
        <canvas id="grafikPemakaian" height="140"></canvas>
    </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikTahunan').getContext('2d');
    const grafikTahunan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'DRD <?= $tahun ?>',
                data: <?= json_encode($values) ?>,
                borderWidth: 2,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true }}
        }
    });
</script>

<script>
    const ctxPmk = document.getElementById('grafikPemakaian').getContext('2d');

    new Chart(ctxPmk, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsPemakaian) !!},
            datasets: [
                {
                    label: '0 m³',
                    data: {!! json_encode($k0) !!},
                    backgroundColor: 'rgba(52,152,219,0.8)'
                },
                {
                    label: '1–5 m³',
                    data: {!! json_encode($k1_5) !!},
                    backgroundColor: 'rgba(46,204,113,0.8)'
                },
                {
                    label: '6–10 m³',
                    data: {!! json_encode($k6_10) !!},
                    backgroundColor: 'rgba(241,196,15,0.8)'
                },
                {
                    label: '11–20 m³',
                    data: {!! json_encode($k11_20) !!},
                    backgroundColor: 'rgba(230,126,34,0.8)'
                },
                {
                    label: '>20 m³',
                    data: {!! json_encode($k20) !!},
                    backgroundColor: 'rgba(231,76,60,0.8)'
                }
            ]
        },
        options: {
            responsive: true,
            scales: { 
                y: { 
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pelanggan'
                    }
                }
            }
        }
    });
</script>

</body>
</html>