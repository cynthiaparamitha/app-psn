<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasangController extends Controller
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

        $listTabul = DB::table('vw_psn_pasang')
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        return view('pasang.index', compact('listTabul', 'tabul'));
    }

    public function getDataApi(Request $request)
    {
        $tabul = $request->tabul;

        if (!$tabul) {
            return response()->json(['tabul' => null, 'data' => []]);
        }

        $data = DB::table('vw_psn_pasang')
            ->where('tabul', $tabul)
            ->orderBy('zona')
            ->get();

        return response()->json([
            'tabul' => $tabul,
            'data'  => $data
        ]);
    }
}