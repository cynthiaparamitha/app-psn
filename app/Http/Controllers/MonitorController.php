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
        } else {
            $data = $query->paginate($perPage)->withQueryString();
        }

        $nextOrder = ($order === 'asc') ? 'desc' : 'asc';

        return view('monitor.tarif', compact(
            'data', 'bulan', 'sort', 'order', 'nextOrder', 'perPage'
        ));
    }
}