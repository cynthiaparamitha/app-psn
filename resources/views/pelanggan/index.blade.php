<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perumda Tirta Patriot - Detail Pelanggan PSN</title>
    
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

        /* --- Tampilan Pagination Mobile Responsive --- */
        .pagination-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .pagination-container .pagination-info {
            font-size: 13px;
            color: #555;
            text-align: center;
        }
        .pagination-container ul {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 4px;
        }
        .pagination-container li button {
            display: inline-block;
            padding: 6px 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s ease;
            min-width: 32px;
            box-sizing: border-box;
        }
        .pagination-container li button:hover {
            background: #2980b9;
        }
        .pagination-container li.active button {
            background: #2c3e50;
            cursor: default;
        }
        .pagination-container li.disabled button {
            background: #e0e0e0;
            color: #a0a0a0;
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
            .pagination-container .pagination-info {
                text-align: left;
                font-size: 14px;
            }
            .pagination-container li button {
                padding: 6px 14px;
                font-size: 13px;
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
        <form id="filterForm">
            <a href="javascript:void(0)" id="btnReset" class="reset-text">⟳ Reset</a>

            <input type="text" name="search" id="search" placeholder="Cari nopel / nama">

            <select name="tarif" id="tarif">
                <option value="">-- Tarif --</option>
                @foreach($tarifList as $t)
                    @php $tTrim = trim($t); @endphp
                    <option value="{{ $tTrim }}">{{ $tTrim }}</option>
                @endforeach
            </select>

            <select name="cabang" id="cabang">
                <option value="">-- K. Pelayanan --</option>
                @foreach($cabangList as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>

            <select name="zona" id="zona">
                <option value="">-- Zona --</option>
                @foreach($zonaList as $z)
                    <option value="{{ $z }}">{{ $z }}</option>
                @endforeach
            </select>

            <select name="status" id="status">
                <option value="">-- Status --</option>
                @foreach($statusList as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>

            <select name="perPage" id="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">All</option>
            </select>

            <button type="submit">Filter</button>

            <a href="javascript:void(0)" id="btnRekapLink" class="rekap-btn">📊 Rekap</a>
        </form>
    </div>

    <div class="report-row">
        <div class="card table-col">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><a href="javascript:void(0)" class="sort-header" data-sort="nopel">No Pel <span id="icon-nopel" class="sort-icon"></span></a></th>
                            <th><a href="javascript:void(0)" class="sort-header" data-sort="nama">Nama <span id="icon-nama" class="sort-icon"></span></a></th>
                            <th>Alamat</th>
                            <th>Tarif</th>
                            <th>No Meter</th>
                            <th>Cabang</th>
                            <th>Zona</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">

                    </tbody>
                </table>
            </div>

            <div id="paginationWrapper">

            </div>
        </div>
    </div>
</div>

<script>
    let currentSort = 'nopel';
    let currentOrder = 'asc';
    let currentPage = 1;

    document.addEventListener("DOMContentLoaded", function() {
        fetchData();

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            currentPage = 1;
            fetchData();
        });

        document.getElementById('btnReset').addEventListener('click', function() {
            document.getElementById('filterForm').reset();
            currentSort = 'nopel';
            currentOrder = 'asc';
            currentPage = 1;
            fetchData();
        });

        document.querySelectorAll('.sort-header').forEach(header => {
            header.addEventListener('click', function() {
                const sortField = this.getAttribute('data-sort');
                if (currentSort === sortField) {
                    currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort = sortField;
                    currentOrder = 'asc';
                }
                fetchData();
            });
        });

        document.getElementById('btnRekapLink').addEventListener('click', function() {
            const params = getFilterParams();
            window.location.href = "{{ route('pelanggan.rekap') }}?" + params.toString();
        });
    });

    function getFilterParams() {
        const params = new URLSearchParams();
        params.append('search', document.getElementById('search').value);
        params.append('tarif', document.getElementById('tarif').value);
        params.append('cabang', document.getElementById('cabang').value);
        params.append('zona', document.getElementById('zona').value);
        params.append('status', document.getElementById('status').value);
        params.append('perPage', document.getElementById('perPage').value);
        params.append('sort', currentSort);
        params.append('order', currentOrder);
        params.append('page', currentPage);
        return params;
    }

    function fetchData() {
        const params = getFilterParams();
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Memuat data...</td></tr>';

        document.getElementById('icon-nopel').innerText = currentSort === 'nopel' ? (currentOrder === 'asc' ? '▲' : '▼') : '';
        document.getElementById('icon-nama').innerText = currentSort === 'nama' ? (currentOrder === 'asc' ? '▲' : '▼') : '';

        fetch("{{ route('pelanggan.api') }}?" + params.toString())
            .then(response => response.json())
            .then(res => {
                tbody.innerHTML = '';
                
                if (res.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Data tidak ditemukan</td></tr>';
                    document.getElementById('paginationWrapper').innerHTML = '';
                    return;
                }

                res.data.forEach(row => {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.nopel ?? ''}</td>
                        <td>${row.nama ?? ''}</td>
                        <td>${row.alamat ?? ''}</td>
                        <td>${row.kode_tarif ?? ''}</td>
                        <td>${row.no_meter ?? ''}</td>
                        <td>${row.cabang ?? ''}</td>
                        <td>${row.zona ?? ''}</td>
                        <td>${row.status ?? ''}</td>
                    `;
                    tbody.appendChild(tr);
                });

                renderPagination(res);
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; color:red;">Gagal mengambil data.</td></tr>';
            });
    }

    function renderPagination(res) {
        const wrapper = document.getElementById('paginationWrapper');
        wrapper.innerHTML = '';

        if (res.is_all) {
            wrapper.innerHTML = `<div style="margin-top: 20px; font-size: 14px; color: #555; text-align:center;">Showing all ${res.total} data</div>`;
            return;
        }

        let html = `
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing <b>${res.from ?? 0}</b> - <b>${res.to ?? 0}</b> of <b>${res.total}</b> data
                </div>
                <div>
                    <ul>
        `;

        const links = res.links;
        const lastPage = res.last_page;

        links.forEach((link, index) => {
            let label = link.label;
            let isPageNumber = !isNaN(label);

            let targetPage = null;
            if (link.url) {
                const urlObj = new URL(link.url);
                targetPage = parseInt(urlObj.searchParams.get('page'));
            }

            if (isPageNumber) {
                let pageNum = parseInt(label);
                
                if (
                    pageNum !== 1 && 
                    pageNum !== lastPage && 
                    Math.abs(pageNum - currentPage) > 1
                ) {
                    if (pageNum === 2 && currentPage > 3) {
                        html += `<li class="disabled"><button type="button" disabled>...</button></li>`;
                    } else if (pageNum === lastPage - 1 && currentPage < lastPage - 2) {
                        html += `<li class="disabled"><button type="button" disabled>...</button></li>`;
                    }
                    return; 
                }
            }

            if (label.includes('Previous')) label = '‹';
            if (label.includes('Next')) label = '›';

            let disabledAttr = link.url ? '' : 'disabled';
            let activeClass = link.active ? 'class="active"' : '';
            let disabledClass = link.url ? '' : 'class="disabled"';

            html += `
                <li ${activeClass} ${disabledClass}>
                    <button type="button" ${disabledAttr} onclick="goToPage(${targetPage})">${label}</button>
                </li>
            `;
        });

        html += `</ul></div></div>`;
        wrapper.innerHTML = html;
    }

    function goToPage(page) {
        currentPage = page;
        fetchData();
    }
</script>
</body>
</html>