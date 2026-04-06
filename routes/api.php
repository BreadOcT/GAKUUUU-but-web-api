<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
    
// Import Controllers
use App\Http\Controllers\Api\AuthController;

// Controller Siswa
use App\Http\Controllers\Api\Siswa\KatalogController;
use App\Http\Controllers\Api\Siswa\PembelajaranController;
use App\Http\Controllers\Api\Siswa\TugasController;
use App\Http\Controllers\Api\Siswa\FeedbackController;

// Controller Admin
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Admin\VerifikasiTentorController;
use App\Http\Controllers\Api\Admin\ContentModerationController;
use App\Http\Controllers\Api\Admin\CustomerServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (Tidak butuh token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Butuh Token Login) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Global Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('userData');
    });
    Route::post('/profile', [AuthController::class, 'updateProfile']); // Update Profil (Siswa/Admin/Tentor)

    // ==========================================
    // ROLE: SISWA
    // ==========================================
    Route::middleware('role:siswa')->prefix('siswa')->group(function () {
        
        // Katalog & Enrollment
        Route::get('/katalog', [KatalogController::class, 'index']);
        Route::get('/katalog/{id}', [KatalogController::class, 'show']);
        Route::post('/enrollment', [KatalogController::class, 'store']); // Daftar Kelas

        // Pembelajaran (LMS)
        Route::get('/dashboard', [PembelajaranController::class, 'dashboard']); // Kelas Saya
        Route::get('/kelas/{id}', [PembelajaranController::class, 'show']); // Detail Kelas & Modul
        Route::get('/modul/{id}/download', [PembelajaranController::class, 'downloadModul']);

        // Tugas
        Route::get('/kelas/{id}/tugas', [TugasController::class, 'index']);
        Route::post('/tugas/{id}/submit', [TugasController::class, 'submit']); // Upload Tugas
        Route::get('/nilai', [TugasController::class, 'rekapNilai']); // Riwayat Nilai

        // Utilitas
        Route::post('/feedback', [FeedbackController::class, 'store']); // Kirim Testimoni
        Route::post('/kontak-cs', [FeedbackController::class, 'kirimKeluhan']); // Kirim Keluhan
    });

    // ==========================================
    // ROLE: ADMIN
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Dashboard Statistik
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Manajemen User
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::get('/users/{id}', [UserManagementController::class, 'show']);
        Route::put('/users/{id}', [UserManagementController::class, 'update']); // Edit/Ban User
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);

        // Verifikasi Tentor
        Route::get('/verifikasi', [VerifikasiTentorController::class, 'index']); // List Pending
        Route::get('/verifikasi/{id}', [VerifikasiTentorController::class, 'show']); // Detail Dokumen
        Route::post('/verifikasi/{id}', [VerifikasiTentorController::class, 'updateStatus']); // Terima/Tolak

        // Moderasi Konten (Feedback)
        Route::get('/feedback', [ContentModerationController::class, 'index']);
        Route::post('/feedback/{id}/approve', [ContentModerationController::class, 'approve']);
        Route::delete('/feedback/{id}', [ContentModerationController::class, 'destroy']);

        // Customer Service
        Route::get('/cs', [CustomerServiceController::class, 'index']); // Inbox
        Route::get('/cs/{id}', [CustomerServiceController::class, 'show']); // Detail Pesan
        Route::post('/cs/{id}/reply', [CustomerServiceController::class, 'reply']); // Balas Pesan
    });

});