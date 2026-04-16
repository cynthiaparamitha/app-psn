<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{
    public function __construct()
    {
        if (!session()->has('user')) {
            redirect('/login')->send();
            exit;
        }
    }

    public function index(Request $request)
    {
        $tabul = $request->tabul;

        // List Tabul
        $listTabul = DB::table('TR_DRD as d')
            ->join('tr_mutasi as m', 'd.Nopel', '=', 'm.Nopel')
            ->whereIn('m.plg_cd', function ($q) {
                $q->select('plg_cd')
                  ->from('tr_mutasi')
                  ->where('mutasi_cd', '6')
                  ->where('tarif_cd', 'PS');
            })
            ->select('d.tabul')
            ->distinct()
            ->orderBy('d.tabul', 'desc')
            ->get();

        // Default ambil tabul terbaru
        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        if (!$tabul) {
            return view('pemakaian.index', [
                'data'      => [],
                'listTabul' => $listTabul,
                'tabul'     => null
            ]);
        }

        // Query sesuai permintaan
        $data = DB::select("
            SELECT 
                c.zona_cd,

                COUNT(CASE WHEN d.kubikasi = 0 THEN d.plg_cd END) AS jumlah_plg_k_0,
                SUM(CASE WHEN d.kubikasi = 0 THEN d.kubikasi ELSE 0 END) AS kubik_0,
                SUM(CASE WHEN d.kubikasi = 0 THEN d.nominal + d.administrasi ELSE 0 END) AS tagihan_k_0,

                COUNT(CASE WHEN d.kubikasi BETWEEN 1 AND 5 THEN d.plg_cd END) AS jumlah_plg_k_1_5,
                SUM(CASE WHEN d.kubikasi BETWEEN 1 AND 5 THEN d.kubikasi ELSE 0 END) AS kubik_1_5,
                SUM(CASE WHEN d.kubikasi BETWEEN 1 AND 5 THEN d.nominal + d.administrasi ELSE 0 END) AS tagihan_k_1_5,

                COUNT(CASE WHEN d.kubikasi BETWEEN 6 AND 10 THEN d.plg_cd END) AS jumlah_plg_k_6_10,
                SUM(CASE WHEN d.kubikasi BETWEEN 6 AND 10 THEN d.kubikasi ELSE 0 END) AS kubik_6_10,
                SUM(CASE WHEN d.kubikasi BETWEEN 6 AND 10 THEN d.nominal + d.administrasi ELSE 0 END) AS tagihan_k_6_10,

                COUNT(CASE WHEN d.kubikasi BETWEEN 11 AND 20 THEN d.plg_cd END) AS jumlah_plg_k_11_20,
                SUM(CASE WHEN d.kubikasi BETWEEN 11 AND 20 THEN d.kubikasi ELSE 0 END) AS kubik_11_20,
                SUM(CASE WHEN d.kubikasi BETWEEN 11 AND 20 THEN d.nominal + d.administrasi ELSE 0 END) AS tagihan_k_11_20,

                COUNT(CASE WHEN d.kubikasi > 20 THEN d.plg_cd END) AS jumlah_plg_lbh_20,
                SUM(CASE WHEN d.kubikasi > 20 THEN d.kubikasi ELSE 0 END) AS kubik_lbh_20,
                SUM(CASE WHEN d.kubikasi > 20 THEN d.nominal + d.administrasi ELSE 0 END) AS tagihan_lbh_20

            FROM tr_drd d
            JOIN ft_cabang c ON d.cabang_cd = c.cabang_cd
            JOIN tr_mutasi m ON m.mutasi_cd = '6' AND d.plg_cd = m.plg_cd
            WHERE m.tarif_cd = 'PS'
            AND d.tabul = ?
            GROUP BY c.zona_cd
            ORDER BY c.zona_cd
        ", [$tabul]);

        return view('pemakaian.index', compact('data','listTabul','tabul'));
    }
}