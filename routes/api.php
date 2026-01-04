<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Siswa\KatalogController;
use App\Http\Controllers\Api\Siswa\PembelajaranController;
use App\Http\Controllers\Api\Siswa\TugasController;
use App\Http\Controllers\Api\Siswa\FeedbackController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:siswa'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/katalog', [KatalogController::class, 'index']); 
    Route::get('/katalog/{matakuliah_id}', [KatalogController::class, 'show']); 
    Route::post('/enrollment', [KatalogController::class, 'store']); 

    Route::get('/dashboard', [PembelajaranController::class, 'dashboard']);
    Route::get('/kelas/{kelas_id}', [PembelajaranController::class, 'show']);
    Route::get('/modul/{modul_id}/download', [PembelajaranController::class, 'downloadModul']);

    Route::post('/tugas/{tugas_id}/submit', [TugasController::class, 'submit']);
    
    Route::get('/nilai', [TugasController::class, 'rekapNilai']);

    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::post('/kontak-cs', [FeedbackController::class, 'kirimKeluhan']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});
