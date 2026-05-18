<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrdController extends Controller
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
        $listTabul = DB::table('vw_drd_zona_psn')
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

        $tabul = $request->tabul ?? ($listTabul->first()->tabul ?? null);
        $zona  = $request->zona ?? null;

        return view('drd.index', compact('listTabul', 'tabul', 'zona'));
    }

    public function getDataApi(Request $request)
    {
        $tabul = $request->tabul;
        $zona  = $request->zona;

        if (!$tabul) {
            $latest = DB::table('vw_drd_zona_psn')->distinct()->orderBy('tabul', 'desc')->first();
            $tabul = $latest ? $latest->tabul : null;
        }

        if (!$zona) {
            $data = DB::table('vw_drd_zona_psn')
                ->where('tabul', $tabul)
                ->orderBy('zona')
                ->get();
            $mode = 'zona';
        } else {
            $data = DB::table('vw_drd_cabang_psn')
                ->where('tabul', $tabul)
                ->where('zona', $zona)
                ->orderBy('cabang')
                ->get();
            $mode = 'cabang';
        }

        return response()->json([
            'mode'  => $mode,
            'tabul' => $tabul,
            'zona'  => $zona,
            'data'  => $data
        ]);
    }
}