<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function __construct()
    {
        if (!session()->has('user')) {
            redirect('/login')->send();
            exit;
        }
    }

    public function tarif(Request $request)
    {
        return view('monitor.tarif');
    }
    
    public function getTarifDataApi(Request $request)
    {
        $bulan = $request->bulan;
        $sort  = $request->sort ?? 'bulan';
        $order = $request->order ?? 'desc';

        $perPage         = $request->perPage ?? 10;
        $allowedPerPage  = [10, 25, 50, 100, 'all'];

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = DB::table('vw_monitor_tarif_ps');

        if (!empty($bulan)) {
            if ($bulan == 6) {
                $query->where('bulan', '>=', 6);
            } else {
                $query->where('bulan', '=', $bulan);
            }
        }

        $query->orderBy($sort, $order);

        if ($perPage === 'all') {
            $data = $query->get();
            
            return response()->json([
                'is_all' => true,
                'data'   => $data,
                'total'  => $data->count()
            ]);
        } else {
            $data = $query->paginate($perPage)->onEachSide(0);
            $dataArray = $data->toArray();

            return response()->json([
                'is_all'     => false,
                'data'       => $dataArray['data'],
                'total'      => $dataArray['total'],
                'first_item' => $dataArray['from'],
                'last_item'  => $dataArray['to'],
                'last_page'  => $dataArray['last_page'],
                'links'      => $dataArray['links']
            ]);
        }
    }
}