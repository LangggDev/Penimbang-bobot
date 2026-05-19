<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'qc') {
        return redirect()->route('qc.dashboard');
    }

    if ($user->role === 'penimbang') {
        return redirect()->route('penimbang.dashboard');
    }

    if ($user->role === 'kasir') {
        return redirect()->route('kasir.dashboard');
    }

    abort(403, 'Role tidak dikenali.');
})->middleware(['auth'])->name('dashboard');

Route::get('/profile', function () {
    return redirect()->route('dashboard');
})->middleware(['auth'])->name('profile.edit');

Route::middleware(['auth'])->group(function () {
    Route::view('/qc/dashboard', 'dashboard.qc')->name('qc.dashboard');
    Route::view('/penimbang/dashboard', 'dashboard.penimbang')->name('penimbang.dashboard');
    Route::view('/kasir/dashboard', 'dashboard.kasir')->name('kasir.dashboard');
});