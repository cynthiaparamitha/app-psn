<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @env('production')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endenv
    <title>Perumda Tirta Patriot - Laporan Tagihan PSN</title>
    
    <link rel="icon" type="image/png" href="/images/logo.png">
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
            background: #eef5ff !important;
        }
        .num { text-align: right; }
        .center { text-align: center; }

        table tr.warning-row { background: #fff9e6; }
        table tr.warning-row td { color: #b7791f; }

        table tr.danger-row { background: #fdf2f2; }
        table tr.danger-row td { color: #c0392b; }

        .pagination-container {
            margin-top: 20px; 
            display: flex; 
            flex-direction: column;
            gap: 10px;
            justify-content: space-between; 
            align-items: center;
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
        .pagination-container li.disabled span {
            background: #bdc3c7;
            color: #ffffff;
            cursor: not-allowed;
        }
        .pagination-container li.active span {
            background: #2c3e50;
            color: #ffffff;
            cursor: default;
        }

        @media (min-width: 768px) {
            body { padding: 20px; }
            h2 { font-size: 24px; }
            .filter-box { width: fit-content; }
            .pagination-container { flex-direction: row; }
        }
        @media (min-width: 1024px) {
            .container-drd { padding-top: 10px; }
            h2 { font-size: 26px; }
            .card { padding: 20px; }
            .report-row { flex-direction: row; flex-wrap: nowrap; }
            .table-col { flex: 0 0 100%; width: 100%; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container-drd">

    <h2>📊 Laporan Tagihan</h2>

    <div class="filter-box">
        <form id="filterForm" onsubmit="event.preventDefault(); fetchData(1);">
            
            <input type="hidden" name="sort" id="currentSort" value="default">

            <a href="javascript:void(0)" id="btnReset" class="reset-text">⟳ Reset</a>

            <input type="text" name="search" id="search" placeholder="Cari nopel / nama">

            <select name="zona" onchange="fetchData(1)">
                <option value="">-- Zona --</option>
                @foreach($zonaList as $z)
                    <option value="{{ $z }}">{{ $z }}</option>
                @endforeach
            </select>

            <select name="cabang" onchange="fetchData(1)">
                <option value="">-- K. Pelayanan --</option>
                @foreach($cabangList as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>

            <select name="status" onchange="fetchData(1)">
                <option value="">-- Status --</option>
                @foreach($statusList as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>

            <select name="tunggakan" onchange="fetchData(1)">
                <option value="">-- Tunggakan --</option>
                <option value="1">Ada Tunggakan</option>
                <option value="0">Tidak Ada Tunggakan</option>
            </select>

            <select name="perPage" onchange="fetchData(1)">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">All</option>
            </select>

            <button type="submit">Filter</button>

        </form>
    </div>

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2"><a href="javascript:void(0)" onclick="changeSort('nopel')">No Pel <span id="icon_nopel"></span></a></th>
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
                            <th><a href="javascript:void(0)" onclick="changeSort('drd')">Bulan <span id="icon_drd"></span></a></th>
                            <th>Tagihan</th>
                            <th><a href="javascript:void(0)" onclick="changeSort('bayar')">Bulan <span id="icon_bayar"></span></a></th>
                            <th>Tagihan</th>
                            <th><a href="javascript:void(0)" onclick="changeSort('tunggak')">Bulan <span id="icon_tunggak"></span></a></th>
                            <th>Tagihan</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="12" style="text-align:center; padding:15px;">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="paginationOuter"></div>

        </div>
    </div>

</div>

<script>
    let currentPage = 1;

    document.addEventListener("DOMContentLoaded", function() {
        fetchData();

        document.getElementById('btnReset').addEventListener('click', function() {
            document.getElementById('filterForm').reset();
            document.getElementById('currentSort').value = 'default';
            resetSortIcons();
            fetchData(1);
        });
    });

    function changeSort(field) {
        const sortInput = document.getElementById('currentSort');
        let current = sortInput.value;
        let direction = 'desc';

        if (current === field + '_desc') {
            direction = 'asc';
        }
        
        sortInput.value = field + '_' + direction;
        
        resetSortIcons();
        
        const iconSpan = document.getElementById('icon_' + field);
        if (direction === 'asc') {
            iconSpan.innerHTML = ' ▲';
        } else {
            iconSpan.innerHTML = ' ▼';
        }

        fetchData(1);
    }

    function resetSortIcons() {
        ['nopel', 'drd', 'bayar', 'tunggak'].forEach(f => {
            const el = document.getElementById('icon_' + f);
            if(el) el.innerHTML = '';
        });
    }

    function fetchData(page = 1) {
        currentPage = page;
        
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);

        if (searchParams.get('perPage') !== 'all') {
            searchParams.append('page', page);
        }

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '<tr><td colspan="12" style="text-align:center; padding:15px;">Memuat data...</td></tr>';

        fetch("{{ route('tagihan.api', [], false) }}?" + searchParams.toString())
            .then(response => response.json())
            .then(res => {
                tbody.innerHTML = '';
                
                if (!res.data || res.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="12" style="text-align:center; padding:15px; color: #888;">
                                <i>Tidak ada data ditemukan...</i>
                            </td>
                        </tr>`;
                    document.getElementById('paginationOuter').innerHTML = '';
                    return;
                }

                res.data.forEach(item => {
                    let tr = document.createElement('tr');
                    
                    let tBulan = parseInt(item.tunggakan_bulan) || 0;
                    if (tBulan >= 6) {
                        tr.className = 'danger-row';
                    } else if (tBulan >= 3) {
                        tr.className = 'warning-row';
                    }

                    tr.innerHTML = `
                        <td class="center">${item.nopel ?? ''}</td>
                        <td>${item.nama ?? ''}</td>
                        <td>${item.alamat ?? ''}</td>
                        <td class="center">${item.status ?? ''}</td>
                        <td class="center">${item.cabang ?? ''}</td>
                        <td class="center">${item.zona ?? ''}</td>
                        <td class="center">${item.drd_bulan ?? 0}</td>
                        <td class="num">${Number(item.drd_tagihan ?? 0).toLocaleString('id-ID')}</td>
                        <td class="center">${item.pembayaran_bulan ?? 0}</td>
                        <td class="num">${Number(item.pembayaran_tagihan ?? 0).toLocaleString('id-ID')}</td>
                        <td class="center"><b>${item.tunggakan_bulan ?? 0}</b></td>
                        <td class="num"><b>${Number(item.tunggakan_tagihan ?? 0).toLocaleString('id-ID')}</b></td>
                    `;
                    tbody.appendChild(tr);
                });

                let fT = res.fullTotal;
                let totalTr = document.createElement('tr');
                totalTr.style = "background: #34495e; color: white; font-weight: bold; border-top: 2px solid #2c3e50; pointer-events: none;";
                totalTr.innerHTML = `
                    <td colspan="6" class="num" style="color: white;">TOTAL :</td>
                    <td class="center">${Number(fT.bulan_drd).toLocaleString('id-ID')}</td>
                    <td class="num">${Number(fT.nom_drd).toLocaleString('id-ID')}</td>
                    <td class="center">${Number(fT.bulan_bayar).toLocaleString('id-ID')}</td>
                    <td class="num">${Number(fT.nom_bayar).toLocaleString('id-ID')}</td>
                    <td class="center">${Number(fT.bulan_tunggak).toLocaleString('id-ID')}</td>
                    <td class="num">${Number(fT.nom_tunggak).toLocaleString('id-ID')}</td>
                `;
                tbody.appendChild(totalTr);

                renderPagination(res);
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="12" style="text-align:center; padding:15px; color:red;">Gagal memuat data dari server.</td></tr>';
            });
    }

    function renderPagination(res) {
        const pOuter = document.getElementById('paginationOuter');
        pOuter.innerHTML = '';

        if (res.is_all) {
            pOuter.innerHTML = `
                <div style="margin-top: 20px; font-size: 14px; color: #555;">
                    Showing all ${Number(res.total).toLocaleString('id-ID')} data
                </div>`;
            return;
        }

        let html = `
            <div class="pagination-container">
                <div style="font-size: 14px; color: #555;">
                    Showing <b>${res.first_item ?? 0}</b> - <b>${res.last_item ?? 0}</b> of <b>${Number(res.total).toLocaleString('id-ID')}</b> data
                </div>
                <div>
                    <ul>
        `;

        const links = res.links;
        const lastPage = res.last_page;

        links.forEach((link) => {
            let label = link.label;
            let isPageNumber = !isNaN(label);

            let targetPage = null;
            if (link.url) {
                const urlObj = new URL(link.url);
                targetPage = parseInt(urlObj.searchParams.get('page'));
            }

            if (isPageNumber) {
                let pageNum = parseInt(label);
                if (pageNum !== 1 && pageNum !== lastPage && Math.abs(pageNum - currentPage) > 1) {
                    if (pageNum === 2 && currentPage > 3) {
                        html += `<li class="disabled"><span>...</span></li>`;
                    } else if (pageNum === lastPage - 1 && currentPage < lastPage - 2) {
                        html += `<li class="disabled"><span>...</span></li>`;
                    }
                    return; 
                }
            }

            if (label.includes('Previous')) label = '‹';
            if (label.includes('Next')) label = '›';

            if (link.active) {
                html += `<li class="active"><span>${label}</span></li>`;
            } else if (!link.url) {
                html += `<li class="disabled"><span>${label}</span></li>`;
            } else {
                html += `<li><a href="javascript:void(0)" onclick="fetchData(${targetPage})">${label}</a></li>`;
            }
        });

        html += `</ul></div></div>`;
        pOuter.innerHTML = html;
    }
</script>
</body>
</html>