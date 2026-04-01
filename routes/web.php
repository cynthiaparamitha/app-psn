<?php

use App\Http\Controllers\DrdController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PelangganController;

Route::get('/', function () {
    return redirect()->route('drd.index');
});

Route::get('/drd', [DrdController::class, 'index'])->name('drd.index');

Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index');

Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

Route::get('/pelanggan/rekap', [PelangganController::class, 'rekap'])
    ->name('pelanggan.rekap');