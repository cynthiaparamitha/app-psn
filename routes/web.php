<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IkhtisarTahunanController;
use App\Http\Controllers\DrdController;
use App\Http\Controllers\LhkController;
use App\Http\Controllers\PasangController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('ikhtisar.tahunan');
});

Route::get('/ikhtisartahunan', [IkhtisarTahunanController::class, 'index'])->name('ikhtisar.tahunan');
Route::get('/api/ikhtisar-tahunan-data', [IkhtisarTahunanController::class, 'getDataApi'])->name('ikhtisar.tahunan.api');

Route::get('/drd', [DrdController::class, 'index'])->name('drd.index');
Route::get('/api/drd-data', [DrdController::class, 'getDataApi'])->name('drd.api');

Route::get('/lhk', [LhkController::class, 'index'])->name('lhk.index');
Route::get('/api/lhk-data', [LhkController::class, 'getDataApi'])->name('lhk.api');

Route::get('/pasang', [PasangController::class, 'index'])->name('pasang.index');
Route::get('/api/pasang-data', [PasangController::class, 'getDataApi'])->name('pasang.api');

Route::get('/pemakaian', [PemakaianController::class, 'index'])->name('pemakaian.index');
Route::get('/api/pemakaian-data', [PemakaianController::class, 'getDataApi'])->name('pemakaian.api');

Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index');
Route::get('/api/mutasi-data', [MutasiController::class, 'getDataApi'])->name('mutasi.api');

Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/api/pelanggan-data', [PelangganController::class, 'getDataApi'])->name('pelanggan.api');

Route::get('/pelanggan/rekap', [PelangganController::class, 'rekap'])->name('pelanggan.rekap');
Route::get('/api/pelanggan-rekap-data', [PelangganController::class, 'getRekapDataApi'])->name('pelanggan.rekap.api');

Route::get('/monitoring-tarif', [MonitorController::class, 'tarif'])->name('monitoring.tarif');
Route::get('/api/monitoring-tarif-data', [MonitorController::class, 'getTarifDataApi'])->name('monitoring.tarif.api');

Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
Route::get('/api/tagihan-data', [TagihanController::class, 'getDataApi'])->name('tagihan.api');