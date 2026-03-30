<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DrdController extends Controller
{
    public function index(Request $request)
    {
        $tabul = $request->tabul;
        $zona  = $request->zona;

        // --- Ambil list tabul PS Mutasi 6 ---
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

        // --- Perbaikan utama: default tabul harus dari listTabul ---
        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        // Jika tetap null → jelas tidak ada data
        if (!$tabul) {
            return view('drd.index_zona', [
                'data'      => [],
                'listTabul' => $listTabul,
                'tabul'     => null
            ]);
        }

        // --- Jika belum pilih zona: tampilkan per zona ---
        if (!$zona) {
            $data = DB::select("
                select
                    c.zona_cd as zona,
                    COUNT(distinct d.nopel) AS jumlah,
                    SUM(d.Kubikasi) AS kubikasi,
                    SUM(d.nominal) as nominal,
                    SUM(d.administrasi) as administrasi,
                    SUM(d.koreksi) as koreksi,
                    SUM(d.total) as total
                from TR_DRD d
                left join tr_mutasi m on d.nopel = m.nopel
                left join ft_cabang c on d.cabang_cd = c.cabang_cd
                where
                    m.plg_cd in (
                        select plg_cd
                        from tr_mutasi
                        where mutasi_cd = '6'
                        and tarif_cd = 'PS'
                    )
                    AND d.tabul = ?
                group by
                    c.zona_cd
                order by
                    c.zona_cd
            ", [$tabul]);

            return view('drd.index_zona', compact('data', 'listTabul', 'tabul'));
        }

        // --- Jika zona dipilih: tampilkan per cabang ---
        $data = DB::select("
            select
                c.cabang_nm as cabang,
                COUNT(distinct d.nopel) AS jumlah,
                SUM(d.Kubikasi) AS kubikasi,
                SUM(d.nominal) as nominal,
                SUM(d.administrasi) as administrasi,
                SUM(d.koreksi) as koreksi,
                SUM(d.total) as total
            from TR_DRD d
            left join tr_mutasi m on d.nopel = m.nopel
            left join ft_cabang c on d.cabang_cd = c.cabang_cd
            where
                m.plg_cd in (
                    select plg_cd
                    from tr_mutasi
                    where mutasi_cd = '6'
                    and tarif_cd = 'PS'
                )
                AND d.tabul = ?
                AND c.zona_cd = ?
            group by
                c.cabang_nm, d.cabang_cd
            order by
                d.cabang_cd
        ", [$tabul, $zona]);

        return view('drd.index_cabang', compact('data','listTabul','tabul','zona'));
    }
}