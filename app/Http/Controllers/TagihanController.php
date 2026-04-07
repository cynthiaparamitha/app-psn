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

        $data = DB::select("
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
                    valid_burek.plg_cd, 
                    COUNT(*) AS bulan_bayar,
                    SUM(d.nominal + d.administrasi) AS total_tagihan_ar
                FROM (
                    SELECT plg_cd, burek
                    FROM tr_ar
                    WHERE ar_cd IN ('001', '003')
                    GROUP BY plg_cd, burek
                    HAVING COUNT(DISTINCT ar_cd) = 2
                ) AS valid_burek
                JOIN tr_drd d 
                    ON valid_burek.plg_cd = d.plg_cd 
                    AND valid_burek.burek = d.tabul
                GROUP BY valid_burek.plg_cd
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
                c.cabang_nm AS cabang,
                c.zona_cd AS zona,

                ISNULL(drd.bulan_drd, 0) AS jumlah_bulan_drd, 
                ISNULL(drd.total_tagihan_drd, 0) AS nominal_drd,

                ISNULL(ar.bulan_bayar, 0) AS jumlah_bulan_bayar,
                ISNULL(ar.total_tagihan_ar, 0) AS nominal_ar,

                ISNULL(tng.bulan_tunggak, 0) AS jumlah_bulan_menunggak,
                ISNULL(tng.total_tagihan_tunggak, 0) AS nominal_tunggakan

            FROM ft_plg p
            LEFT JOIN ft_cabang c ON p.cabang_cd = c.cabang_cd
            LEFT JOIN CTE_DRD_TOTAL drd ON p.plg_cd = drd.plg_cd
            LEFT JOIN CTE_AR_LENGKAP ar ON p.plg_cd = ar.plg_cd
            LEFT JOIN CTE_TUNGGAKAN tng ON p.plg_cd = tng.plg_cd

            WHERE EXISTS (
                SELECT 1 
                FROM tr_mutasi m 
                WHERE m.plg_cd = p.plg_cd 
                AND m.mutasi_cd = '6' 
                AND m.tarif_cd = 'PS'
            )
        ");

        $data = collect($data);

        if ($search) {
            $data = $data->filter(function ($row) use ($search) {
                return str_contains(strtolower($row->nopel), strtolower($search))
                    || str_contains(strtolower($row->nama), strtolower($search));
            });
        }

        if ($zona) {
            $data = $data->where('zona', $zona);
        }

        if ($cabang) {
            $data = $data->where('cabang', $cabang);
        }

        if ($status) {
            $data = $data->where('status', $status);
        }

        if ($tunggakan == "1") {
            $data = $data->filter(fn($row) => $row->jumlah_bulan_menunggak > 0);
        }

        $data = $data->sortBy('nopel')->values();

        $zonaList   = $data->pluck('zona')->unique()->sort()->values();
        $cabangList = $data->pluck('cabang')->unique()->sort()->values();
        $statusList = $data->pluck('status')->unique()->sort()->values();

        return view('tagihan.index', compact(
            'data', 'search', 'zona', 'cabang', 'status', 'tunggakan',
            'zonaList', 'cabangList', 'statusList'
        ));
    }
}