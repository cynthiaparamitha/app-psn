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
        $raw = DB::select("
            SELECT status, cabang, zona FROM dbo.psn_tagihan
        ");
        $allData = collect($raw);

        $zonaList   = $allData->pluck('zona')->filter()->unique()->sort()->values();
        $cabangList = $allData->pluck('cabang')->filter()->unique()->sort()->values();
        $statusList = $allData->pluck('status')->filter()->unique()->sort()->values();

        return view('tagihan.index', compact('zonaList', 'cabangList', 'statusList'));
    }

    public function getDataApi(Request $request)
    {
        $search    = $request->search;
        $zona      = $request->zona;
        $cabang    = $request->cabang;
        $status    = $request->status;
        $tunggakan = $request->tunggakan;
        $perPage   = $request->perPage ?? 10;
        $sort      = $request->sort ?? 'default';

        $raw = DB::select("
            SELECT 
                nopel,
                nama,
                alamat,
                status,
                cabang,
                zona,
                drd_bulan,
                drd_tagihan,
                pembayaran_bulan,
                pembayaran_tagihan,
                tunggakan_bulan,
                tunggakan_tagihan
            FROM dbo.psn_tagihan
            ORDER BY nopel ASC
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

        switch ($sort) {
            case 'nopel_asc':   
                $data = $data->sortBy('nopel', SORT_NATURAL); 
                break;
            case 'nopel_desc': 
                $data = $data->sortByDesc('nopel', SORT_NATURAL); 
                break;
            case 'drd_asc':    
                $data = $data->sortBy(['drd_bulan', 'nopel']); 
                break;
            case 'drd_desc':   
                $data = $data->sortBy([['drd_bulan', 'desc'], ['nopel', 'asc']]); 
                break;
            case 'bayar_asc':  
                $data = $data->sortBy(['pembayaran_bulan', 'nopel']); 
                break;
            case 'bayar_desc': 
                $data = $data->sortBy([['pembayaran_bulan', 'desc'], ['nopel', 'asc']]); 
                break;
            case 'tunggak_asc':  
                $data = $data->sortBy(['tunggakan_bulan', 'nopel']); 
                break;
            case 'tunggak_desc': 
                $data = $data->sortBy([['tunggakan_bulan', 'desc'], ['nopel', 'asc']]); 
                break;
            default:
                $data = $data->sortBy([['drd_bulan', 'desc'], ['nopel', 'asc']]);
        }

        $data = $data->values();

        $fullTotal = [
            'bulan_drd'     => $data->sum('drd_bulan'),
            'nom_drd'       => $data->sum('drd_tagihan'),
            'bulan_bayar'   => $data->sum('pembayaran_bulan'),
            'nom_bayar'     => $data->sum('pembayaran_tagihan'),
            'bulan_tunggak' => $data->sum('tunggakan_bulan'),
            'nom_tunggak'   => $data->sum('tunggakan_tagihan'),
        ];

        if ($perPage === 'all') {
            return response()->json([
                'is_all'    => true,
                'data'      => $data,
                'total'     => $data->count(),
                'fullTotal' => $fullTotal
            ]);
        } else {
            $current = LengthAwarePaginator::resolveCurrentPage();
            $offset  = ($current - 1) * $perPage;
            $paged   = $data->slice($offset, $perPage)->values();

            $paginator = new LengthAwarePaginator(
                $paged,
                $data->count(),
                $perPage,
                $current,
                ['path' => url()->current()]
            );

            $dataArray = $paginator->onEachSide(0)->toArray();

            return response()->json([
                'is_all'     => false,
                'data'       => $dataArray['data'],
                'total'      => $dataArray['total'],
                'first_item' => $dataArray['from'],
                'last_item'  => $dataArray['to'],
                'last_page'  => $dataArray['last_page'],
                'links'      => $dataArray['links'],
                'fullTotal'  => $fullTotal
            ]);
        }
    }
}