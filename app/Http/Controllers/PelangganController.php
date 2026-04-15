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

        $nextOrder = ($order === 'asc') ? 'desc' : 'asc';

        $basePSN = DB::table('ft_plg as p')
            ->leftJoin('ft_tarif_2013 as t', 'p.tarif_cd', '=', 't.tarif_cd')
            ->leftJoin('ft_cabang as c', 'p.cabang_cd', '=', 'c.cabang_cd')
            ->whereIn('p.plg_cd', function ($sub) {
                $sub->select('plg_cd')
                    ->from('tr_mutasi')
                    ->where('mutasi_cd', '6')
                    ->where('tarif_cd', 'PS');
            })
            ->whereNotIn('p.plg_cd', function ($sub2) {
                $sub2->select('plg_cd')
                    ->from('tr_mutasi')
                    ->where('mutasi_cd', '8')
                    ->where('asalnya', 'like', '%PSN%')
                    ->where('nama', 'not like', '%PSN%')
                    ->where('nopel', '!=', '010303008157');
            });

        $tarifList  = (clone $basePSN)->select('t.tarif_cd')->distinct()->pluck('tarif_cd');
        $cabangList = (clone $basePSN)->select('c.cabang_nm')->distinct()->pluck('c.cabang_nm');
        $zonaList   = (clone $basePSN)->select('c.zona_cd')->distinct()->pluck('c.zona_cd');
        $statusList = (clone $basePSN)->select('p.status')->distinct()->pluck('status');

        $query = (clone $basePSN)
            ->select(
                'p.nopel',
                'p.nama',
                'p.alamat',
                't.tarif_cd as kode_tarif',
                'p.meter_no as no_meter',
                'c.cabang_nm as cabang',
                'c.zona_cd as zona',
                'p.status'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.nopel', 'like', "%$search%")
                  ->orWhere('p.nama', 'like', "%$search%");
            });
        }

        if ($tarif)  $query->where('t.tarif_cd', $tarif);
        if ($cabang) $query->where('c.cabang_nm', $cabang);
        if ($status) $query->where('p.status', $status);
        if ($zona)   $query->where('c.zona_cd', $zona);

        $query->orderBy(DB::raw("LOWER($sort)"), $order);

        if ($perPage === 'all') {
            $data = $query->get();
        } else {
            $data = $query->paginate($perPage)->withQueryString();
        }

        return view('pelanggan.index', compact(
            'data', 'search', 'tarif', 'cabang', 'status', 'zona',
            'sort', 'order', 'nextOrder',
            'tarifList', 'cabangList', 'statusList', 'zonaList',
            'perPage'
        ));
    }

    public function rekap(Request $request)
    {
        $search = $request->search;
        $tarif  = $request->tarif;
        $cabang = $request->cabang;
        $status = $request->status;
        $zona   = $request->zona;

        $query = DB::table('ft_plg as p')
            ->leftJoin('ft_tarif_2013 as t', 'p.tarif_cd', '=', 't.tarif_cd')
            ->leftJoin('ft_cabang as c', 'p.cabang_cd', '=', 'c.cabang_cd')
            ->whereIn('p.plg_cd', function ($sub) {
                $sub->select('plg_cd')
                    ->from('tr_mutasi')
                    ->where('mutasi_cd', '6')
                    ->where('tarif_cd', 'PS');
            })
            ->whereNotIn('p.plg_cd', function ($sub2) {
                $sub2->select('plg_cd')
                    ->from('tr_mutasi')
                    ->where('mutasi_cd', '8')
                    ->where('asalnya', 'like', '%PSN%')
                    ->where('nama', 'not like', '%PSN%')
                    ->where('nopel', '!=', '010303008157');
            })
            ->select(
                'p.nopel',
                'p.nama',
                'p.alamat',
                't.tarif_cd as kode_tarif',
                'p.meter_no as no_meter',
                'c.cabang_nm as cabang',
                'c.zona_cd as zona',
                'p.status'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.nopel', 'like', "%$search%")
                  ->orWhere('p.nama', 'like', "%$search%");
            });
        }

        if ($tarif)  $query->where('t.tarif_cd', $tarif);
        if ($cabang) $query->where('c.cabang_nm', $cabang);
        if ($status) $query->where('p.status', $status);
        if ($zona)   $query->where('c.zona_cd', $zona);

        $data = $query->get();

        return view('pelanggan.rekap', [
            'rekapTarif'  => $data->groupBy('kode_tarif')->map->count(),
            'rekapCabang' => $data->groupBy('cabang')->map->count(),
            'rekapZona'   => $data->groupBy('zona')->map->count(),
            'rekapStatus' => $data->groupBy('status')->map->count(),
            'total'       => $data->count(),
        ]);
    }
}