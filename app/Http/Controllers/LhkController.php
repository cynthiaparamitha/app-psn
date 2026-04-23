<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LhkController extends Controller
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

        // List tabul
        $listTabul = DB::table('vw_zona_lhk')
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        if (!$tabul) {
            return view('lhk.index_zona', [
                'data'      => [],
                'listTabul' => $listTabul,
                'tabul'     => null,
            ]);
        }

        if (!$zona) {

            $data = DB::table('vw_zona_lhk')
                ->where('tabul', $tabul)
                ->orderBy('zona')
                ->get()
                ->map(function ($row) {
                    $row->total = $row->air + $row->administrasi + $row->denda + $row->NAL;
                    return $row;
                });

            return view('lhk.index_zona', compact('data', 'listTabul', 'tabul'));
        }

        $data = DB::table('vw_cabang_lhk')
            ->where('tabul', $tabul)
            ->where('zona', $zona)
            ->orderBy('cabang')
            ->get()
            ->map(function ($row) {
                $row->total = $row->air + $row->administrasi + $row->denda + $row->NAL;
                return $row;
            });

        return view('lhk.index_cabang', compact('data', 'listTabul', 'tabul', 'zona'));
    }
}