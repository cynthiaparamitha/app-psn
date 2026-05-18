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

        $baseQuery = DB::table('TR_mutasi')
            ->where('tabul', '>=', '202412')
            ->whereNotIn('tabul', ['210307', '301306'])
            ->whereRaw("LEN(tabul)=6")
            ->where('tabul', 'not like', '%-%')
            ->whereRaw("ISNUMERIC(tabul)=1");

        $listTabul = (clone $baseQuery)
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

        if (!$tabul) {
            $tabul = (clone $baseQuery)->max('tabul');
        }

        return view('mutasi.index', compact('listTabul', 'tabul'));
    }

    public function getDataApi(Request $request)
    {
        $tabul = $request->tabul;

        if (!$tabul) {
            return response()->json(['tabul' => null, 'data' => []]);
        }

        $data = DB::table('vw_mutasi_psn')
            ->where('tabul', $tabul)
            ->orderBy('cabang')
            ->get();

        return response()->json([
            'tabul' => $tabul,
            'data'  => $data
        ]);
    }
}