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

        if (!$tabul) {
            return view('pasang.index', [
                'data'      => [],
                'listTabul' => $listTabul,
                'tabul'     => null,
            ]);
        }

        $data = DB::table('vw_psn_pasang')
            ->where('tabul', $tabul)
            ->orderBy('zona')
            ->get();

        return view('pasang.index', compact('data', 'listTabul', 'tabul'));
    }
}