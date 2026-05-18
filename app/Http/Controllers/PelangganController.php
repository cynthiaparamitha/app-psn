<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
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
        $tarifList  = DB::table('vw_pelanggan_psn')->whereNotNull('kode_tarif')->distinct()->pluck('kode_tarif');
        $cabangList = DB::table('vw_pelanggan_psn')->whereNotNull('cabang')->distinct()->pluck('cabang');
        $zonaList   = DB::table('vw_pelanggan_psn')->whereNotNull('zona')->distinct()->pluck('zona');
        $statusList = DB::table('vw_pelanggan_psn')->whereNotNull('status')->distinct()->pluck('status');

        return view('pelanggan.index', compact('tarifList', 'cabangList', 'statusList', 'zonaList'));
    }

    public function getDataApi(Request $request)
    {
        $search = $request->search;
        $tarif  = $request->tarif;
        $cabang = $request->cabang;
        $status = $request->status;
        $zona   = $request->zona;

        $perPage = $request->perPage ?? 10;
        $allowedPerPage = [10, 25, 50, 100, 'all'];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $allowedSort = ['nopel', 'nama'];
        $sort  = $request->sort ?? 'nopel';
        $order = $request->order ?? 'asc';
        if (!in_array($sort, $allowedSort)) {
            $sort = 'nopel';
        }

        $query = DB::table('vw_pelanggan_psn');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nopel', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%");
            });
        }

        if ($tarif)  $query->where('kode_tarif', $tarif);
        if ($cabang) $query->where('cabang', $cabang);
        if ($status) $query->where('status', $status);
        if ($zona)   $query->where('zona', $zona);

        $query->orderBy(DB::raw("LOWER($sort)"), $order);

        if ($perPage === 'all') {
            $data = $query->get();
            return response()->json([
                'is_all' => true,
                'data' => $data,
                'total' => $data->count()
            ]);
        } else {
            $data = $query->paginate($perPage);
            return response()->json([
                'is_all' => false,
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'links' => $data->linkCollection() // Membantu merender pagination manual
            ]);
        }
    }

    public function rekap(Request $request)
    {
        return view('pelanggan.rekap');
    }

    public function getRekapDataApi(Request $request)
    {
        $query = DB::table('vw_pelanggan_psn');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nopel', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%");
            });
        }

        if ($request->tarif)  $query->where('kode_tarif', $request->tarif);
        if ($request->cabang) $query->where('cabang', $request->cabang);
        if ($request->status) $query->where('status', $request->status);
        if ($request->zona)   $query->where('zona', $request->zona);

        $data = $query->get();

        return response()->json([
            'rekapTarif'  => $data->groupBy('kode_tarif')->map->count(),
            'rekapCabang' => $data->groupBy('cabang')->map->count(),
            'rekapZona'   => $data->groupBy('zona')->map->count(),
            'rekapStatus' => $data->groupBy('status')->map->count(),
            'total'       => $data->count(),
        ]);
    }
}