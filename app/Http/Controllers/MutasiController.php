<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MutasiController extends Controller
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

        $data = DB::table('vw_mutasi_psn')
            ->where('tabul', $tabul)
            ->orderBy('cabang')
            ->get();

        $totals = [
            'golongan' => $data->sum('golongan'),
            'alamat_pelanggan' => $data->sum('alamat_pelanggan'),
            'ganti_meter' => $data->sum('ganti_meter'),
            'pengaktifan_kembali' => $data->sum('pengaktifan_kembali'),
            'pelanggan_baru' => $data->sum('pelanggan_baru'),
            'aktif_ke_nonaktif' => $data->sum('aktif_ke_nonaktif'),
            'nama_pelanggan' => $data->sum('nama_pelanggan'),
            'stand_meter' => $data->sum('stand_meter'),
            'ganti_nopel' => $data->sum('ganti_nopel'),
            'no_handphone' => $data->sum('no_handphone'),
        ];

        return view('mutasi.index', compact('data','listTabul','tabul','totals'));
    }
}