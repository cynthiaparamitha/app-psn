<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $tabul = $request->tabul;

        if (!$tabul) {
            $tabul = DB::table('TR_mutasi')
                ->where('tabul','>=','202412')
                ->whereNotIn('tabul',['210307','301306'])
                ->whereRaw("LEN(tabul)=6")
                ->where('tabul','not like','%-%')
                ->whereRaw("ISNUMERIC(tabul)=1")
                ->max('tabul');
        }

        $listTabul = DB::table('TR_mutasi')
            ->where('tabul','>=','202412')
            ->whereNotIn('tabul',['210307','301306'])
            ->whereRaw("LEN(tabul)=6")
            ->where('tabul','not like','%-%')
            ->whereRaw("ISNUMERIC(tabul)=1")
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul','desc')
            ->get();

        $data = DB::select("
            select
                c.cabang_nm as cabang,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '1' THEN m.nopel END) as golongan,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '3' THEN m.nopel END) as alamat_pelanggan,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '4' THEN m.nopel END) as ganti_meter,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '5' THEN m.nopel END) as pengaktifan_kembali,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '6' THEN m.nopel END) as pelanggan_baru,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '7' THEN m.nopel END) as aktif_ke_nonaktif,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = '8' THEN m.nopel END) as nama_pelanggan,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = 'C' THEN m.nopel END) as stand_meter,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = 'D' THEN m.nopel END) as ganti_nopel,
                COUNT(DISTINCT CASE WHEN m.mutasi_cd = 'G' THEN m.nopel END) as no_handphone
            from TR_mutasi m
            left join ft_cabang c on m.cabang_cd = c.cabang_cd
            WHERE
                m.plg_cd in (
                    select plg_cd
                    from tr_mutasi
                    where mutasi_cd = '6'
                    and tarif_cd = 'PS'
                )
                and m.tabul = ?
                and m.tabul >= '202412'
                and m.tabul not in ('210307','301306')
                and LEN(m.tabul) = 6
                and m.tabul not like '%-%'
                and ISNUMERIC(m.tabul) = 1
            group by
                c.cabang_nm, m.cabang_cd
            order by
                m.cabang_cd
        ", [$tabul]);

        $totals = [
            'golongan' => 0,
            'alamat_pelanggan' => 0,
            'ganti_meter' => 0,
            'pengaktifan_kembali' => 0,
            'pelanggan_baru' => 0,
            'aktif_ke_nonaktif' => 0,
            'nama_pelanggan' => 0,
            'stand_meter' => 0,
            'ganti_nopel' => 0,
            'no_handphone' => 0,
        ];

        foreach ($data as $row) {
            foreach ($totals as $key => $val) {
                $totals[$key] += (int) $row->$key;
            }
        }

        return view('mutasi.index', compact('data','listTabul','tabul','totals'));
    }
}