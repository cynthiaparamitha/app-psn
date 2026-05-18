<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perumda Tirta Patriot - Monitoring Tarif PSN</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
        <form id="filterForm" onsubmit="event.preventDefault(); fetchData();">

            <a href="javascript:void(0)" id="btnReset" class="reset-text">⟳ Reset</a>

            <select name="bulan" onchange="fetchData()">
                <option value="">Semua Data</option>
                <option value="1">1 Bulan</option>
                <option value="2">2 Bulan</option>
                <option value="3">3 Bulan</option>
                <option value="4">4 Bulan</option>
                <option value="5">5 Bulan</option>
                <option value="6">≥ 6 Bulan</option>
            </select>

            <select name="sort" onchange="fetchData()">
                <option value="bulan">Sort by Bulan</option>
            </select>

            <select name="order" onchange="fetchData()">
                <option value="desc">Bulan Terbanyak</option>
                <option value="asc">Bulan Tersedikit</option>
            </select>

            <select name="perPage" onchange="fetchData()">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">All</option>
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
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="5" style="text-align:center; padding:15px;">Memuat data...</td>
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
            fetchData(1);
        });
    });

    function fetchData(page = 1) {
        currentPage = page;
        
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);

        if (searchParams.get('perPage') !== 'all') {
            searchParams.append('page', page);
        }

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:15px;">Memuat data...</td></tr>';

        fetch("{{ route('monitoring.tarif.api') }}?" + searchParams.toString())
            .then(response => response.json())
            .then(res => {
                tbody.innerHTML = '';
                
                if (!res.data || res.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" style="text-align:center; padding:15px; color: #888;">
                                <i>Tidak ada data ditemukan...</i>
                            </td>
                        </tr>`;
                    document.getElementById('paginationOuter').innerHTML = '';
                    return;
                }

                res.data.forEach(item => {
                    let tr = document.createElement('tr');
                    if (parseInt(item.bulan) >= 6) {
                        tr.className = 'warning';
                    }

                    tr.innerHTML = `
                        <td style="text-align: center;">${item.Plg_CD ?? ''}</td>
                        <td style="text-align: center;">${item.Nopel ?? ''}</td>
                        <td>${item.Nama ?? ''}</td>
                        <td style="text-align: center;">${item.Tarif ?? ''}</td>
                        <td style="text-align: center;"><b>${item.bulan ?? 0}</b></td>
                    `;
                    tbody.appendChild(tr);
                });

                renderPagination(res);
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:15px; color:red;">Gagal memuat data dari server.</td></tr>';
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