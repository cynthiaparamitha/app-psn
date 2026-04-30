<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IkhtisarTahunanController extends Controller
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
        $tahun = $request->tahun;

        $listTahun = DB::table('vw_drd_zona_psn')
            ->select(DB::raw("DISTINCT LEFT(tabul,4) AS tahun"))
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');

        if (!$tahun) {
            $tahun = $listTahun->last();
        }

        $data = DB::table('vw_drd_zona_psn')
        ->select(
            DB::raw("LEFT(tabul,4) AS tahun"),
            DB::raw("RIGHT(tabul,2) AS bulan"),
            DB::raw("SUM(total) AS total")
        )
        ->whereRaw("LEFT(tabul,4) = ?", [$tahun])
        ->groupBy(DB::raw("LEFT(tabul,4)"), DB::raw("RIGHT(tabul,2)"))
        ->orderBy(DB::raw("RIGHT(tabul,2)"))
        ->get();

        $kubikasiData = DB::table('vw_drd_zona_psn')
            ->select(
                DB::raw("RIGHT(tabul,2) AS bulan"),
                DB::raw("SUM(kubikasi) AS total_kubik")
            )
            ->whereRaw("LEFT(tabul,4) = ?", [$tahun])
            ->groupBy(DB::raw("RIGHT(tabul,2)"))
            ->orderBy(DB::raw("RIGHT(tabul,2)"))
            ->get();

        $bulanList = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        $labels = [];
        $values = [];
        $kubik = [];

        foreach ($bulanList as $kode => $nama) {
            $labels[] = $nama;
            $values[] = (int) ($data->firstWhere('bulan', $kode)->total ?? 0);
            $kubik[] = (int) ($kubikasiData->firstWhere('bulan', $kode)->total_kubik ?? 0);
        }

        return view('ikhtisar_tahunan', compact(
            'labels','values','tahun','kubik','listTahun'
        ));
    }
}