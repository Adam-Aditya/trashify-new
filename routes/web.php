<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\SetorSampahController;
use App\Models\SetorSampah;
use App\Http\Controllers\AuthPenggunaController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/dashboard', function () {
    $data = SetorSampah::latest()->get();
    return view('dashboard', compact('data'));
})->name('dashboard');

Route::get('/dashboard', function () {
    $data = App\Models\SetorSampah::latest()->get();
    return view('dashboard', compact('data'));
})->name('dashboard');

// Pastikan dibungkus middleware auth agar data Auth::user() tidak bernilai null/error
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [AuthPenggunaController::class, 'showProfil'])->name('profil.show');
    Route::post('/profil/update', [AuthPenggunaController::class, 'updateProfil'])->name('profil.update');

    // Pastikan diletakkan di dalam Route::middleware(['auth'])->group(function () { ... })
    Route::get('/tukar-poin', [AuthPenggunaController::class, 'showTukarPoin'])->name('poin.tukar');
    Route::post('/tukar-poin', [AuthPenggunaController::class, 'prosesTukarPoin'])->name('poin.proses');
});

Route::resource('sampah', SampahController::class);

Route::get('/setor-sampah', [SetorSampahController::class, 'create'])->name('setor.create');
Route::post('/setor-sampah', [SetorSampahController::class, 'store'])->name('setor.store');

Route::get('/history', [SetorSampahController::class, 'index'])->name('history.index');
Route::get('/history/{id}/edit', [SetorSampahController::class, 'edit'])->name('history.edit');
Route::put('/history/{id}', [SetorSampahController::class, 'update'])->name('history.update');
Route::delete('/history/{id}', [SetorSampahController::class, 'destroy'])->name('history.destroy');

Route::get('/login-user', [AuthPenggunaController::class, 'showForm'])->name('login.user');
Route::post('/proses-signup', [AuthPenggunaController::class, 'register'])->name('register.process');
Route::post('/proses-login', [AuthPenggunaController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthPenggunaController::class, 'logout'])->name('logout');