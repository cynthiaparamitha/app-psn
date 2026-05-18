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

        $listTabul = DB::table('vw_zona_lhk')
            ->select('tabul')
            ->distinct()
            ->orderBy('tabul', 'desc')
            ->get();

        if (!$tabul) {
            $tabul = $listTabul->first()->tabul ?? null;
        }

        return view('lhk.index', compact('listTabul', 'tabul', 'zona'));
    }

    public function getDataApi(Request $request)
    {
        $tabul = $request->tabul;
        $zona  = $request->zona;

        if (!$tabul) {
            return response()->json(['mode' => 'zona', 'tabul' => null, 'zona' => null, 'data' => []]);
        }

        if ($zona) {
            $data = DB::table('vw_cabang_lhk')
                ->where('tabul', $tabul)
                ->where('zona', $zona)
                ->orderBy('cabang')
                ->get()
                ->map(function ($row) {
                    $row->total = (float)($row->air + $row->administrasi + $row->denda + $row->NAL);
                    return $row;
                });

            return response()->json([
                'mode'  => 'cabang',
                'tabul' => $tabul,
                'zona'  => $zona,
                'data'  => $data
            ]);
        }

        $data = DB::table('vw_zona_lhk')
            ->where('tabul', $tabul)
            ->orderBy('zona')
            ->get()
            ->map(function ($row) {
                $row->total = (float)($row->air + $row->administrasi + $row->denda + $row->NAL);
                return $row;
            });

        return response()->json([
            'mode'  => 'zona',
            'tabul' => $tabul,
            'zona'  => null,
            'data'  => $data
        ]);
    }
}