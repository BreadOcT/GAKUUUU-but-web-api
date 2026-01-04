<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Siswa\KatalogController;
use App\Http\Controllers\Api\Siswa\PembelajaranController;
use App\Http\Controllers\Api\Siswa\TugasController;
use App\Http\Controllers\Api\Siswa\FeedbackController;

// --- AUTHENTICATION (FR-01, FR-02) ---
// Use Case 4.2 (Login) & 4.3 (Registrasi)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Group Middleware: User harus login & Role harus SISWA
Route::middleware(['auth:sanctum', 'role:siswa'])->group(function () {

    // --- LOGOUT ---
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- KATALOG & PENDAFTARAN (FR-06, Use Case 4.6, 4.8, 4.4) ---
    // Melihat daftar semua mata kuliah
    Route::get('/katalog', [KatalogController::class, 'index']); 
    // Detail MK (termasuk list tentor yang tersedia)
    Route::get('/katalog/{matakuliah_id}', [KatalogController::class, 'show']); 
    // Proses Enroll: Memilih MK, Tentor, dan Jadwal sekaligus
    Route::post('/enrollment', [KatalogController::class, 'store']); 

    // --- PEMBELAJARAN (FR-10, Use Case 4.7) ---
    // Dashboard: List MK yang sedang diambil siswa
    Route::get('/dashboard', [PembelajaranController::class, 'dashboard']);
    // Detail Kelas: Melihat materi/modul di dalam kelas
    Route::get('/kelas/{kelas_id}', [PembelajaranController::class, 'show']);
    // Download/Akses File Modul
    Route::get('/modul/{modul_id}/download', [PembelajaranController::class, 'downloadModul']);

    // --- TUGAS & PENGUMPULAN (FR-11, FR-12, Use Case 4.9) ---
    // Upload Tugas/Kuis
    Route::post('/tugas/{tugas_id}/submit', [TugasController::class, 'submit']);
    
    // --- NILAI (FR-14, Use Case 4.10) ---
    // Cek Nilai (Transkrip)
    Route::get('/nilai', [TugasController::class, 'rekapNilai']);

    // --- UTILITAS (FR-15, FR-17, Use Case 4.5) ---
    // Kirim Feedback/Testimoni
    Route::post('/feedback', [FeedbackController::class, 'store']);
    // Update Profil Siswa
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});