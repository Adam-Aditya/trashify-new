<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\SetorSampahController;
use App\Models\SetorSampah;
use App\Http\Controllers\AuthPenggunaController;
use App\Http\Controllers\AuthPengepulController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/dashboard', function () {
    $data = SetorSampah::latest()->get();
    return view('dashboard', compact('data'));
})->name('dashboard');

Route::get('/dashboard', function () {
    $data = App\Models\SetorSampah::where('user_id', Auth::id())->latest()->get();
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

// ROUTE UNTUK PENGEPUL

// Route Auth Pengepul
Route::get('/mitra/auth', [AuthPengepulController::class, 'showForm'])->name('pengepul.auth');
Route::post('/mitra/register', [AuthPengepulController::class, 'register'])->name('pengepul.register.process');
Route::post('/mitra/login', [AuthPengepulController::class, 'login'])->name('pengepul.login.process');

// Proteksi Pengisian Kategori Toko menggunakan Middleware Auth Guard Pengepul
Route::middleware(['auth:pengepul'])->group(function () {
    
    // Pengisian Kategori & Data Toko Pertama Kali
    Route::get('/mitra/kategori-toko', [AuthPengepulController::class, 'showKategoriToko'])->name('pengepul.toko');
    Route::post('/mitra/kategori-toko', [AuthPengepulController::class, 'simpanKategoriToko'])->name('pengepul.toko.save');
    
    // Dashboard Utama Pengepul
    Route::get('/dashboard-pengepul', [AuthPengepulController::class, 'showDashboard'])->name('pengepul.dashboard');
    
    // API Perubahan Status Buka/Tutup Toko (AJAX)
    Route::post('/mitra/toggle-status', [AuthPengepulController::class, 'toggleStatus'])->name('pengepul.toggle');
    
    // TAMBAHKAN INI: Proses Keluar Akun Khusus Pengepul
    Route::post('/mitra/logout', [AuthPengepulController::class, 'logout'])->name('pengepul.logout');
});

Route::middleware(['auth:pengepul'])->group(function () {
    // Jalur rute profil pengepul
    Route::get('/mitra/profil', [AuthPengepulController::class, 'showProfil'])->name('pengepul.profil');
    Route::post('/mitra/profil/update', [AuthPengepulController::class, 'updateProfil'])->name('pengepul.profil.update');
});

Route::middleware(['auth:pengepul'])->group(function () {
    Route::get('/mitra/wallet/topup', [AuthPengepulController::class, 'showTopupForm'])->name('pengepul.poin.tukar'); // Sesuaikan name rute link sidebar kamu
    Route::post('/mitra/wallet/topup/proses', [AuthPengepulController::class, 'prosesTopup'])->name('pengepul.poin.proses');
});

Route::post('/mitra/logout', [AuthPengepulController::class, 'logout'])->name('pengepul.logout');