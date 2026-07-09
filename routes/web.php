<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])->name('home');

Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'redirectDashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/profile', [\App\Http\Controllers\HomeController::class, 'redirectProfile'])
    ->middleware(['auth'])
    ->name('profile.edit');


Route::middleware(['auth'])->group(function () {
    Route::view('/qc/dashboard', 'dashboard.qc')->name('qc.dashboard');
    Route::view('/penimbang/dashboard', 'dashboard.penimbang')->name('penimbang.dashboard');
    Route::view('/kasir/dashboard', 'dashboard.kasir')->name('kasir.dashboard');
});

Route::middleware(['auth'])
    ->prefix('penimbang')
    ->name('penimbang.')
    ->group(function () {

    Route::get('/pelanggan/{id}/edit', [\App\Http\Controllers\Penimbang\PenimbangPelangganController::class, 'edit'])
        ->name('pelanggan.edit');


    Route::put('/pelanggan/{id}', [\App\Http\Controllers\Penimbang\PenimbangPelangganController::class, 'update'])
        ->name('pelanggan.update');


    Route::delete('/pelanggan/{id}', [\App\Http\Controllers\Penimbang\PenimbangPelangganController::class, 'destroy'])
        ->name('pelanggan.destroy');

        Route::get('/pelanggan/create', [\App\Http\Controllers\Penimbang\PenimbangPelangganController::class, 'create'])
        ->name('pelanggan.create');


    Route::post('/pelanggan', [\App\Http\Controllers\Penimbang\PenimbangPelangganController::class, 'store'])
        ->name('pelanggan.store');

        Route::get('/pelanggan', [\App\Http\Controllers\Penimbang\PenimbangPelangganController::class, 'index'])
        ->name('pelanggan.index');

        Route::get('/transaksi', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'index'])
            ->name('transaksi.index');

        Route::get('/transaksi/create', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'create'])
            ->name('transaksi.create');

        Route::post('/transaksi', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'store'])
            ->name('transaksi.store');

        Route::get('/pelanggan/{id}/timbangan-pertama', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'timbanganPertama'])
            ->name('pelanggan.timbangan-pertama');

        Route::post('/pelanggan/{id}/timbangan-pertama', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'simpanTimbanganPertama'])
            ->name('pelanggan.timbangan-pertama.store');

        Route::get('/transaksi/{id}/timbangan-kedua', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'timbanganKedua'])
            ->name('transaksi.timbangan-kedua');

        Route::post('/transaksi/{id}/timbang-bertahap', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'simpanTimbangBertahap'])
            ->name('transaksi.timbang-bertahap.store');

        Route::post('/transaksi/{id}/selesai-penimbangan', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'selesaiPenimbangan'])
            ->name('transaksi.selesai-penimbangan');

        Route::get('/transaksi/{id}/detail', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'detail'])
            ->name('transaksi.show');

        Route::get('/dashboard', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/transaksi/{id}/print-antrian', [\App\Http\Controllers\Penimbang\PenimbangTransaksiController::class, 'printAntrian'])
            ->name('transaksi.print-antrian');

    });

    Route::middleware(['auth'])
    ->prefix('qc')
    ->name('qc.')
    ->group(function () {

    Route::get('/riwayat', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'riwayatIndex'])
        ->name('riwayat.index');


Route::get('/riwayat/{qcId}/edit', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'riwayatEdit'])
    ->name('riwayat.edit');


Route::put('/riwayat/{qcId}', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'riwayatUpdate'])
    ->name('riwayat.update');

        Route::get('/penilaian/{detailId}/create', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'create'])
            ->name('penilaian.create');


Route::post('/penilaian/{detailId}', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'store'])
    ->name('penilaian.store');

       Route::get('/dashboard', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'dashboard'])
            ->name('dashboard');


        Route::get('/penilaian', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'index'])
            ->name('penilaian.index');

        Route::get('/fuzzy', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'fuzzyIndex'])
            ->name('fuzzy.index');


Route::get('/fuzzy/{fuzzyId}', [\App\Http\Controllers\Qc\QcPenilaianController::class, 'fuzzyShow'])
    ->name('fuzzy.show');


    });

    Route::middleware(['auth'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

    Route::get('/pembayaran', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'index'])
        ->name('pembayaran.index');

    Route::get('/pembayaran/{transaksiId}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'show'])
        ->name('pembayaran.show');


    Route::post('/pembayaran/{transaksiId}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'store'])
        ->name('pembayaran.store');

    Route::get('/kasbon', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonIndex'])
        ->name('kasbon.index');


    Route::get('/kasbon/create', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonCreate'])
        ->name('kasbon.create');


    Route::post('/kasbon', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonStore'])
        ->name('kasbon.store');


    Route::get('/kasbon/{id}/edit', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonEdit'])
        ->name('kasbon.edit');


    Route::put('/kasbon/{id}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonUpdate'])
        ->name('kasbon.update');


    Route::delete('/kasbon/{id}', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'kasbonDestroy'])
        ->name('kasbon.destroy');

    Route::get('/laporan', [\App\Http\Controllers\Kasir\KasirLaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/dashboard', [\App\Http\Controllers\Kasir\KasirPembayaranController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/laporan/pembayaran/{id}', [\App\Http\Controllers\Kasir\KasirLaporanController::class, 'detail'])
        ->name('laporan.detail');

    Route::get('/laporan/pembayaran/{id}/print', [\App\Http\Controllers\Kasir\KasirLaporanController::class, 'print'])
        ->name('laporan.print');

    });