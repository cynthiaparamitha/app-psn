<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        $tabul = $request->tabul;
        $zona  = $request->zona;

        $listTabul = DB::table('vw_drd_zona_psn')
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        if (!$tabul) {
            return view('drd.index_zona', [
                'data'      => [],
                'listTabul' => $listTabul,
                'tabul'     => null,
            ]);
        }

        if (!$zona) {
            $data = DB::table('vw_drd_zona_psn')
                ->where('tabul', $tabul)
                ->orderBy('zona')
                ->get();

            return view('drd.index_zona', compact('data', 'listTabul', 'tabul'));
        }

        $data = DB::table('vw_drd_cabang_psn')
            ->where('tabul', $tabul)
            ->where('zona', $zona)
            ->orderBy('cabang')
            ->get();

        return view('drd.index_cabang', compact('data', 'listTabul', 'tabul', 'zona'));
    }
}