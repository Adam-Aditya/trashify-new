<?php

use Illuminate\Support\Facades\Route;
use App\Models\SetorSampah;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\SetorSampahController;
use App\Http\Controllers\AuthPenggunaController;
use App\Http\Controllers\AuthPengepulController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ==========================================
//          HALAMAN UMUM / LANDING
// ==========================================
Route::get('/', function () {
    return view('landing');
})->name('landing');



// RUTE PENGGUNA

// 🛠️ FIX SINTAKSIS LOGIN: Dibuat terpisah agar Laravel mengenali KEDUA nama rute secara sah!
Route::get('/login-user', [AuthPenggunaController::class, 'showForm'])->name('login.user');
Route::get('/login', [AuthPenggunaController::class, 'showForm'])->name('login'); // Fallback cadangan untuk middleware auth

Route::post('/proses-signup', [AuthPenggunaController::class, 'register'])->name('register.process');
Route::post('/proses-login', [AuthPenggunaController::class, 'login'])->name('login.process');

// AUTH ROUTES USER (Wajib Login)
Route::middleware(['auth'])->group(function () {
    
    //  KODE BARU (MENGHITUNG JARAK DAN MENAMPILKAN PENGEPUL YANG BUKA)
    Route::get('/dashboard', function () {
        // 1. Ambil data koordinat lokasi user yang sedang login ("lat,lng")
        $user = Auth::user();
        $userLoc = explode(',', $user->location ?? '0,0');
        $userLat = isset($userLoc[0]) ? (float)$userLoc[0] : 0;
        $userLng = isset($userLoc[1]) ? (float)$userLoc[1] : 0;

        // 2. Ambil data pengepul yang berstatus BUKA (is_buka = 1) menggunakan rumus Haversine
        $daftarToko = DB::table('pengepuls')
            ->where('is_buka', 1)
            ->select('*')
            ->selectRaw(
                "( 6371 * acos( cos( radians(?) ) * cos( radians( SUBSTRING_INDEX(location, ',', 1) ) ) * cos( radians( SUBSTRING_INDEX(location, ',', -1) ) - radians(?) ) + sin( radians(?) ) * sin( radians( SUBSTRING_INDEX(location, ',', 1) ) ) ) ) AS jarak", 
                [$userLat, $userLng, $userLat]
            )
            ->orderBy('jarak', 'asc') // Urutkan dari yang jaraknya paling dekat
            ->get();

        // Kirim variabel $daftarToko ke view dashboard.blade.php yang baru
        return view('dashboard', compact('daftarToko'));
    })->name('dashboard');

    // Profil User
    Route::get('/profil', [AuthPenggunaController::class, 'showProfil'])->name('profil.show');
    Route::post('/profil/update', [AuthPenggunaController::class, 'updateProfil'])->name('profil.update');

    // Wallet / Tukar Poin User
    Route::get('/tukar-poin', [AuthPenggunaController::class, 'showTukarPoin'])->name('poin.tukar');
    Route::post('/tukar-poin', [AuthPenggunaController::class, 'prosesTukarPoin'])->name('poin.proses');

    // Manajemen Proses Setor Sampah Sistem Terintegrasi Lapak Pengepul
    Route::get('/setor-sampah', [SetorSampahController::class, 'create'])->name('setor.create');
    
    // 🛠️ TAMBAHKAN ROUTE BARU INI:
    Route::get('/setor-sampah/pilih-pengepul', [SetorSampahController::class, 'pilihPengepul'])->name('setor.pilih-pengepul');
    Route::post('/setor-sampah/konfirmasi', [SetorSampahController::class, 'store'])->name('setor.store');
    
    Route::get('/history', [SetorSampahController::class, 'index'])->name('history.index');
    Route::get('/history/{id}/edit', [SetorSampahController::class, 'edit'])->name('history.edit');
    Route::put('/history/{id}', [SetorSampahController::class, 'update'])->name('history.update');
    Route::delete('/history/{id}', [SetorSampahController::class, 'destroy'])->name('history.destroy');

    Route::post('/logout', [AuthPenggunaController::class, 'logout'])->name('logout');
    
});


//  RUTE PENGEPUL

// GUEST ROUTES (Belum Login)
Route::get('/mitra/auth', [AuthPengepulController::class, 'showForm'])->name('pengepul.auth');
Route::post('/mitra/register', [AuthPengepulController::class, 'register'])->name('pengepul.register.process');
Route::post('/mitra/login', [AuthPengepulController::class, 'login'])->name('pengepul.login.process');

// AUTH ROUTES MITRA (🛠️ FIX: Semua grup terpisah pengepul disatukan di sini agar rapi)
Route::middleware(['auth:pengepul'])->group(function () {
    
    // Data Toko Mitra
    Route::get('/mitra/kategori-toko', [AuthPengepulController::class, 'showKategoriToko'])->name('pengepul.toko');
    Route::post('/mitra/kategori-toko', [AuthPengepulController::class, 'simpanKategoriToko'])->name('pengepul.toko.save');
    
    // Dashboard & Status Buka/Tutup Lapak
    Route::get('/dashboard-pengepul', [AuthPengepulController::class, 'showDashboard'])->name('pengepul.dashboard');
    Route::post('/mitra/toggle-status', [AuthPengepulController::class, 'toggleStatus'])->name('pengepul.toggle');

    Route::put('/mitra/setoran/{id}/status', [SetorSampahController::class, 'updateStatus'])->name('pengepul.setoran.status');

    Route::get('/mitra/setoran/{id}/detail', [SetorSampahController::class, 'showDetail'])->name('pengepul.setoran.detail');

    Route::get('/mitra/setoran/{id}/penjemputan', [SetorSampahController::class, 'showPenjemputan'])->name('pengepul.setoran.penjemputan');
    Route::post('/mitra/setoran/{id}/selesai', [SetorSampahController::class, 'selesaiTransaksi'])->name('pengepul.setoran.selesai');
    
    // Profil Lapak
    Route::get('/mitra/profil', [AuthPengepulController::class, 'showProfil'])->name('pengepul.profil');
    Route::post('/mitra/profil/update', [AuthPengepulController::class, 'updateProfil'])->name('pengepul.profil.update');

    // Wallet Top Up Pengepul
    Route::get('/mitra/wallet/topup', [AuthPengepulController::class, 'showTopupForm'])->name('pengepul.poin.tukar'); 
    Route::post('/mitra/wallet/topup/proses', [AuthPengepulController::class, 'prosesTopup'])->name('pengepul.poin.proses');

    // 🛠️ FIX LOGOUT: Rute logout luar grup dihapus, disisakan 1 yang aman di dalam middleware proteksi
    Route::post('/mitra/logout', [AuthPengepulController::class, 'logout'])->name('pengepul.logout');
});


// ==========================================
//          RESOURCE ROUTE ADDITIONAL
// ==========================================
Route::resource('sampah', SampahController::class);