<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function tarif(Request $request)
    {
        $bulan = $request->bulan;
        $order = $request->sort ?? 'desc'; // default: paling banyak

        $query = DB::table('TR_DRD as d')
            ->join('FT_Plg as p', 'p.Plg_CD', '=', 'd.Plg_CD')
            ->select(
                'd.Plg_CD',
                'p.Nopel',
                'p.Nama',
                'p.Tarif_CD as Tarif',
                DB::raw('COUNT(*) as bulan')
            )
            ->where('d.Tarif_CD', 'PS')
            ->where('p.Tarif_CD', 'PS')
            ->groupBy('d.Plg_CD', 'p.Nopel', 'p.Nama', 'p.Tarif_CD');

        if (!empty($bulan)) {
            if ($bulan == 6) {
                $query->having(DB::raw('COUNT(*)'), '>=', 6);
            } else {
                $query->having(DB::raw('COUNT(*)'), '=', $bulan);
            }
        }

        $data = $query->orderBy('bulan', $order)->get();

        return view('monitor.tarif', compact('data', 'bulan', 'order'));
    }
}