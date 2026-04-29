<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;

use App\Http\Controllers\SetorSampahController;
use App\Models\SetorSampah;

Route::get('/dashboard', function () {
    $data = SetorSampah::latest()->get();
    return view('dashboard', compact('data'));
});

Route::resource('sampah', SampahController::class);

Route::get('/setor-sampah', [SetorSampahController::class, 'create']);
Route::post('/setor-sampah', [SetorSampahController::class, 'store']);
Route::get('/history', [SetorSampahController::class, 'index']);
Route::get('/history/{id}/edit', [SetorSampahController::class, 'edit']);
Route::put('/history/{id}', [SetorSampahController::class, 'update']);
Route::delete('/history/{id}', [SetorSampahController::class, 'destroy']);