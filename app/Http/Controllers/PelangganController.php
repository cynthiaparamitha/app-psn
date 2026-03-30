<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $tarif  = $request->tarif;
        $cabang = $request->cabang;
        $status = $request->status;
        $zona   = $request->zona;

        $sort  = $request->sort ?? 'nopel';
        $order = $request->order ?? 'asc';

        $allowedSort = [
            'nopel','nama','alamat','kode_tarif',
            'no_meter','cabang','zona','status'
        ];

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
            });

        $tarifList = (clone $basePSN)
            ->select('t.tarif_cd')
            ->distinct()
            ->orderBy('t.tarif_cd')
            ->pluck('tarif_cd');

        $cabangList = (clone $basePSN)
            ->select('c.cabang_nm')
            ->distinct()
            ->orderBy('c.cabang_nm')
            ->pluck('cabang_nm');

        $zonaList = (clone $basePSN)
            ->select('c.zona_cd')
            ->distinct()
            ->orderBy('c.zona_cd')
            ->pluck('zona_cd');

        $statusList = (clone $basePSN)
            ->select('p.status')
            ->distinct()
            ->orderBy('p.status')
            ->pluck('status');

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

        $data = $query->orderBy($sort, $order)->get();

        return view('pelanggan.index', compact(
            'data','search','tarif','cabang','status','zona',
            'sort','order','nextOrder',
            'tarifList','cabangList','statusList','zonaList'
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

        $rekapTarif  = $data->groupBy('kode_tarif')->map->count();
        $rekapCabang = $data->groupBy('cabang')->map->count();
        $rekapZona   = $data->groupBy('zona')->map->count();
        $rekapStatus = $data->groupBy('status')->map->count();
        $total       = $data->count();

        return view('pelanggan.rekap', compact(
            'rekapTarif','rekapCabang','rekapZona','rekapStatus','total'
        ));
    }
}