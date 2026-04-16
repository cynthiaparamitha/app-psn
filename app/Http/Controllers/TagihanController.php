<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class TagihanController extends Controller
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
        $search    = $request->search;
        $zona      = $request->zona;
        $cabang    = $request->cabang;
        $status    = $request->status;
        $tunggakan = $request->tunggakan;
        $perPage   = $request->perPage ?? 10;

        $raw = DB::select("
            SELECT 
                nopel,
                nama,
                alamat,
                status,
                cabang,         -- cabang_nm
                zona,           -- zona_cd
                drd_bulan,
                drd_tagihan,
                pembayaran_bulan,
                pembayaran_tagihan,
                tunggakan_bulan,
                tunggakan_tagihan
            FROM dbo.psn_tagihan
        ");

        $data = collect($raw);

        if ($search) {
            $s = strtolower($search);
            $data = $data->filter(fn($r) =>
                str_contains(strtolower($r->nopel), $s) ||
                str_contains(strtolower($r->nama), $s)
            );
        }

        if ($zona)   $data = $data->where('zona', $zona);
        if ($cabang) $data = $data->where('cabang', $cabang);
        if ($status) $data = $data->where('status', $status);

        if ($tunggakan === "1") $data = $data->where('tunggakan_bulan', '>', 0);
        if ($tunggakan === "0") $data = $data->where('tunggakan_bulan', '=', 0);

        $sort = $request->sort ?? 'default';

        switch ($sort) {
            case 'nopel_asc':  $data = $data->sortBy('nopel'); break;
            case 'nopel_desc': $data = $data->sortByDesc('nopel'); break;

            case 'drd_asc':    $data = $data->sortBy('drd_bulan'); break;
            case 'drd_desc':   $data = $data->sortByDesc('drd_bulan'); break;

            case 'bayar_asc':  $data = $data->sortBy('pembayaran_bulan'); break;
            case 'bayar_desc': $data = $data->sortByDesc('pembayaran_bulan'); break;

            case 'tunggak_asc':  $data = $data->sortBy('tunggakan_bulan'); break;
            case 'tunggak_desc': $data = $data->sortByDesc('tunggakan_bulan'); break;

            default:
                $data = $data->sortByDesc('drd_bulan');
        }

        $data = $data->values();

        $fullTotal = [
            'bulan_drd'   => $data->sum('drd_bulan'),
            'nom_drd'     => $data->sum('drd_tagihan'),

            'bulan_bayar' => $data->sum('pembayaran_bulan'),
            'nom_bayar'   => $data->sum('pembayaran_tagihan'),

            'bulan_tunggak' => $data->sum('tunggakan_bulan'),
            'nom_tunggak'   => $data->sum('tunggakan_tagihan'),
        ];

        $zonaList   = $data->pluck('zona')->unique()->sort()->values();
        $cabangList = $data->pluck('cabang')->unique()->sort()->values();
        $statusList = $data->pluck('status')->unique()->sort()->values();

        if ($perPage !== 'all') {
            $current = $request->page ?? 1;
            $offset  = ($current - 1) * $perPage;

            $paged = $data->slice($offset, $perPage)->values();

            $data = new LengthAwarePaginator(
                $paged,
                $data->count(),
                $perPage,
                $current,
                ['path' => url()->current(), 'query' => $request->query()]
            );
        }

        return view('tagihan.index', compact(
            'data', 'search', 'zona', 'cabang', 'status', 'tunggakan',
            'zonaList', 'cabangList', 'statusList', 'perPage', 'sort',
            'fullTotal'
        ));
    }
}