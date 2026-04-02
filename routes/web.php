<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrdController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\MonitorController;   // ← Tambahkan ini
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('drd.index');
});

Route::get('/drd', [DrdController::class, 'index'])->name('drd.index');

Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index');

Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

Route::get('/pelanggan/rekap', [PelangganController::class, 'rekap'])->name('pelanggan.rekap');

Route::get('/monitoring-tarif', [MonitorController::class, 'tarif'])->name('monitoring.tarif');