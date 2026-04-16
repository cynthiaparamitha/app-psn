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
            WITH
            CTE_PLG AS (
                SELECT 
                    p.plg_cd, 
                    p.nopel, 
                    p.nama, 
                    p.alamat, 
                    p.status,
                    p.cabang_cd
                FROM FT_Plg p
                INNER JOIN TR_Mutasi m 
                    ON p.Plg_CD = m.Plg_CD
                WHERE m.Mutasi_CD = '6'
            ),

            CTE_DRD_TOTAL AS (
                SELECT 
                    plg_cd, 
                    COUNT(*) AS bulan_drd,
                    SUM(nominal + administrasi) AS total_tagihan_drd
                FROM tr_drd
                WHERE plg_cd IN (SELECT plg_cd FROM CTE_PLG)
                GROUP BY plg_cd
            ),

            CTE_AR_LENGKAP AS (
                SELECT 
                    v.plg_cd, 
                    COUNT(*) AS bulan_bayar,
                    SUM(d.nominal + d.administrasi) AS total_tagihan_ar
                FROM (
                    SELECT DISTINCT plg_cd, burek
                    FROM tr_ar
                    WHERE ar_cd IN ('001', '003')
                    GROUP BY plg_cd, burek
                ) v
                JOIN tr_drd d 
                    ON v.plg_cd = d.plg_cd 
                    AND v.burek = d.tabul
                GROUP BY v.plg_cd
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

            FROM CTE_PLG p
            INNER JOIN TR_Mutasi m 
                ON m.Plg_CD = p.Plg_CD 
                AND m.Mutasi_CD = '6'
            LEFT JOIN ft_cabang c 
                ON p.cabang_cd = c.cabang_cd
            LEFT JOIN CTE_DRD_TOTAL drd 
                ON p.plg_cd = drd.plg_cd
            LEFT JOIN CTE_AR_LENGKAP ar 
                ON p.plg_cd = ar.plg_cd
            LEFT JOIN CTE_TUNGGAKAN tng 
                ON p.plg_cd = tng.plg_cd

            WHERE m.tarif_cd = 'PS'
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

        if ($tunggakan === "1") {
            $data = $data->filter(fn($row) => $row->jumlah_bulan_menunggak > 0);
        }

        if ($tunggakan === "0") {
            $data = $data->filter(fn($row) => $row->jumlah_bulan_menunggak == 0);
        }

        $sort = $request->sort ?? 'default';

        switch ($sort) {

            case 'nopel_asc':
                $data = $data->sortBy('nopel');
            break;

            case 'nopel_desc':
                $data = $data->sortByDesc('nopel');
            break;

            case 'drd_asc':
                $data = $data
                    ->sortBy('nopel')
                    ->sortBy('jumlah_bulan_drd');
            break;

            case 'drd_desc':
                $data = $data
                    ->sortBy('nopel')
                    ->sortByDesc('jumlah_bulan_drd');
            break;

            case 'bayar_asc':
                $data = $data
                    ->sortBy('nopel')
                    ->sortBy('jumlah_bulan_bayar');
            break;

            case 'bayar_desc':
                $data = $data
                    ->sortBy('nopel')
                    ->sortByDesc('jumlah_bulan_bayar');
            break;

            case 'tunggak_asc':
                $data = $data
                    ->sortBy('nopel')
                    ->sortBy('jumlah_bulan_menunggak');
            break;

            case 'tunggak_desc':
                $data = $data
                    ->sortBy('nopel')
                    ->sortByDesc('jumlah_bulan_menunggak');
            break;

            default:
                $data = $data
                    ->sortBy('nopel')
                    ->sortByDesc('jumlah_bulan_drd');
            break;
        }

        $data = $data->values();

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
            'totals', 'sort'
        ));
    }
}