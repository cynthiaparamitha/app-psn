<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DrdController extends Controller
{
    public function __construct()
    {
        // Cek apakah sudah login
        if (!session()->has('user')) {
            redirect('/login')->send();
            exit;
        }
    }

    public function index(Request $request)
    {
        $tabul = $request->tabul;
        $zona  = $request->zona;

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

        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        if (!$tabul) {
            return view('drd.index_zona', [
                'data'      => [],
                'listTabul' => $listTabul,
                'tabul'     => null
            ]);
        }

        if (!$zona) {
            $data = DB::select("
            select
                c.zona_cd as zona,
                COUNT(distinct d.plg_cd) AS jumlah,
                SUM(d.Kubikasi) AS kubikasi,
                SUM(d.nominal) as nominal,
                SUM(d.administrasi) as administrasi,
                SUM(d.koreksi) as koreksi,
                SUM(d.nominal+d.administrasi) as total
            from TR_DRD d
            inner join tr_mutasi m on d.plg_cd = m.plg_cd and m.Mutasi_CD='6'
            left join ft_cabang c on d.cabang_cd = c.cabang_cd
            where
                m.plg_cd in (
                    select plg_cd
                    from tr_mutasi
                    where mutasi_cd = '6'
                    and tarif_cd = 'PS'
                )
                -- AND m.plg_cd not in (
            --     select plg_cd 
            --     from tr_mutasi 
            --     where mutasi_cd = '8' 
            --     and asalnya like '%PSN%' 
            --     and nama not like '%PSN%' 
            --     and not nopel = '010303008157'
            -- )
                AND d.tabul = ?
            group by
                c.zona_cd
            order by
                c.zona_cd
        ", [$tabul]);

            return view('drd.index_zona', compact('data', 'listTabul', 'tabul'));
        }

            $data = DB::select("
            select
                c.cabang_nm as cabang,
                COUNT(distinct d.nopel) AS jumlah,
                SUM(d.Kubikasi) AS kubikasi,
                SUM(d.nominal) as nominal,
                SUM(d.administrasi) as administrasi,
                SUM(d.koreksi) as koreksi,
                SUM(d.nominal+d.administrasi) as total
            from TR_DRD d
            inner join tr_mutasi m on d.plg_cd = m.plg_cd and m.Mutasi_CD='6'
            left join ft_cabang c on d.cabang_cd = c.cabang_cd
            where
                m.plg_cd in (
                    select plg_cd
                    from tr_mutasi
                    where mutasi_cd = '6'
                    and tarif_cd = 'PS'
                )
                -- AND m.plg_cd not in (
                --     select plg_cd 
                --     from tr_mutasi 
                --     where mutasi_cd = '8' 
                --     and asalnya like '%PSN%' 
                --     and nama not like '%PSN%' 
                --     and not nopel = '010303008157'
                -- )
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