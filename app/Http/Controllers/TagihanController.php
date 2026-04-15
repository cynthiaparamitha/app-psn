<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->search;
        $zona        = $request->zona;
        $cabang      = $request->cabang;
        $status      = $request->status;
        $tunggakan   = $request->tunggakan;
        $perPage     = $request->perPage ?? 10;

        $rawData = DB::select("
        WITH CTE_DRD_TOTAL AS (
            SELECT 
                plg_cd, 
                COUNT(*) AS bulan_drd,
                SUM(nominal + administrasi) AS total_tagihan_drd
            FROM tr_drd
            GROUP BY plg_cd
        ),
        CTE_AR_LENGKAP AS (
            SELECT 
                x.plg_cd, 
                COUNT(*) AS bulan_bayar,
                SUM(d.nominal + d.administrasi) AS total_tagihan_ar
            FROM (
                SELECT plg_cd, burek
                FROM tr_ar
                WHERE ar_cd IN ('001','003')
                GROUP BY plg_cd, burek
                HAVING COUNT(DISTINCT ar_cd)=2
            ) x
            JOIN tr_drd d 
                ON d.plg_cd = x.plg_cd 
                AND d.tabul = x.burek
            GROUP BY x.plg_cd
        ),
        CTE_TUNGGAKAN AS (
            SELECT 
                plg_cd,
                COUNT(*) AS bulan_tunggak,
                SUM(nominal + administrasi) AS total_tagihan_tunggak
            FROM tr_drd
            WHERE saldo_ar > 0
            GROUP BY plg_cd
        )

        SELECT 
            p.plg_cd, 
            p.nopel, 
            p.nama, 
            p.alamat, 
            p.status,
            cab.cabang_nm AS cabang,
            cab.zona_cd AS zona,

            ISNULL(drd.bulan_drd, 0) AS jumlah_bulan_drd,
            ISNULL(drd.total_tagihan_drd, 0) AS nominal_drd,

            ISNULL(ar.bulan_bayar, 0) AS jumlah_bulan_bayar,
            ISNULL(ar.total_tagihan_ar, 0) AS nominal_ar,

            ISNULL(tng.bulan_tunggak, 0) AS jumlah_bulan_menunggak,
            ISNULL(tng.total_tagihan_tunggak, 0) AS nominal_tunggakan

        FROM ft_plg p
        INNER JOIN tr_mutasi m 
            ON m.plg_cd = p.plg_cd 
            AND m.mutasi_cd = '6' 
            AND m.tarif_cd = 'PS'

        LEFT JOIN ft_cabang cab 
            ON cab.cabang_cd = p.cabang_cd
        LEFT JOIN CTE_DRD_TOTAL drd 
            ON drd.plg_cd = p.plg_cd
        LEFT JOIN CTE_AR_LENGKAP ar 
            ON ar.plg_cd = p.plg_cd
        LEFT JOIN CTE_TUNGGAKAN tng 
            ON tng.plg_cd = p.plg_cd

        WHERE NOT EXISTS (
            SELECT 1
            FROM tr_mutasi mx
            WHERE mx.plg_cd = p.plg_cd
            AND mx.mutasi_cd = '8'
            AND mx.asalnya LIKE '%PSN%'
            AND mx.nama NOT LIKE '%PSN%'
            AND mx.nopel <> '010303008157'
        )
        ");

        $data = collect($rawData);

        if ($search) {
            $searchLower = strtolower($search);
            $data = $data->filter(function ($row) use ($searchLower) {
                return str_contains(strtolower($row->nopel), $searchLower)
                    || str_contains(strtolower($row->nama), $searchLower);
            });
        }

        if ($zona)   $data = $data->where('zona', $zona);
        if ($cabang) $data = $data->where('cabang', $cabang);
        if ($status) $data = $data->where('status', $status);

        if ($tunggakan == "1") {
            $data = $data->filter(fn($row) => $row->jumlah_bulan_menunggak > 0);
        }

        $data = $data->sortBy('nopel')->values();

        $zonaList   = $data->pluck('zona')->unique()->sort()->values();
        $cabangList = $data->pluck('cabang')->unique()->sort()->values();
        $statusList = $data->pluck('status')->unique()->sort()->values();

        $totals = [
            'bulan_drd'       => $data->sum('jumlah_bulan_drd'),
            'nominal_drd'     => $data->sum('nominal_drd'),
            'bulan_bayar'     => $data->sum('jumlah_bulan_bayar'),
            'nominal_bayar'   => $data->sum('nominal_ar'),
            'bulan_tunggakan' => $data->sum('jumlah_bulan_menunggak'),
            'nominal_tunggak' => $data->sum('nominal_tunggakan'),
        ];

        if ($perPage !== 'all') {
            $currentPage = request()->input('page', 1);
            $offset      = ($currentPage - 1) * $perPage;

            $pagedData = $data->slice($offset, $perPage)->values();

            $data = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData,
                $data->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => request()->query()]
            );
        }

        return view('tagihan.index', compact(
            'data', 'search', 'zona', 'cabang', 'status', 'tunggakan',
            'zonaList', 'cabangList', 'statusList', 'perPage',
            'totals'
        ));
    }
}