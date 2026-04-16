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

        $listTabul = DB::table('vw_pemakaian_psn')
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

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

        $data = DB::table('vw_pemakaian_psn')
            ->where('tabul', $tabul)
            ->orderBy('zona_cd')
            ->get();

        return view('pemakaian.index', compact('data','listTabul','tabul'));
    }
}