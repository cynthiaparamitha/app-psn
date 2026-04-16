<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function tarif(Request $request)
    {
        $bulan = $request->bulan;
        $sort  = $request->sort ?? 'bulan';
        $order = $request->order ?? 'desc';

        $perPage         = $request->perPage ?? 10;
        $allowedPerPage  = [10, 25, 50, 100, 'all'];

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = DB::table('TR_DRD as d')
        ->join('FT_Plg as p', 'p.Plg_CD', '=', 'd.Plg_CD')
        ->join('TR_Mutasi as m', function ($join) {
            $join->on('m.Plg_CD', '=', 'p.Plg_CD')
                ->where('m.Mutasi_CD', '=', '6');
        })
        ->select(
            'd.Plg_CD',
            'p.Nopel',
            'p.Nama',
            'p.Tarif_CD as Tarif',
            DB::raw('COUNT(*) as bulan')
        )
        ->where('d.Tarif_CD', 'PS')
        ->where('p.Tarif_CD', 'PS')
        // ->whereNotIn('m.Plg_CD', function($sub){
        //     $sub->select('plg_cd')
        //         ->from('tr_mutasi')
        //         ->where('mutasi_cd', '8')
        //         ->where('asalnya', 'like', '%PSN%')
        //         ->where('nama', 'not like', '%PSN%')
        //         ->whereRaw("not nopel = '010303008157'");
        // })
        ->groupBy('d.Plg_CD', 'p.Nopel', 'p.Nama', 'p.Tarif_CD');

        if (!empty($bulan)) {
            if ($bulan == 6) {
                $query->having(DB::raw('COUNT(*)'), '>=', 6);
            } else {
                $query->having(DB::raw('COUNT(*)'), '=', $bulan);
            }
        }

        $query->orderBy($sort, $order);

        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage)->withQueryString();
        }

        $nextOrder = ($order === 'asc') ? 'desc' : 'asc';

        return view('monitor.tarif', compact(
            'data', 'bulan', 'sort', 'order', 'nextOrder', 'perPage'
        ));
    }
}