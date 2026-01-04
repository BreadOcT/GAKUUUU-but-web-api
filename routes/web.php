<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Siswa\PageController;

// Halaman Depan (Landing Page)
Route::get('/', function () { return view('welcome'); });

// --- AUTH PAGES ---
// Menampilkan Form Login & Register
Route::get('/login', [PageController::class, 'showLogin'])->name('login');
Route::get('/register', [PageController::class, 'showRegister'])->name('register');

// Proses Login/Register biasanya ditangani controller yang sama dengan API 
// atau controller khusus web yang me-return redirect, bukan JSON.

// Group Middleware: User harus login di Web & Role harus SISWA
Route::middleware(['auth:web', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    // --- DASHBOARD & KATALOG ---
    // Menampilkan Dashboard Siswa (View: siswa.dashboard)
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    
    // Menampilkan Halaman Katalog (View: siswa.katalog.index)
    Route::get('/katalog', [PageController::class, 'katalog'])->name('katalog.index');
    
    // Menampilkan Detail MK & Form Pilih Tentor (View: siswa.katalog.show)
    Route::get('/katalog/{id}', [PageController::class, 'detailMatakuliah'])->name('katalog.show');

    // --- KELAS & PEMBELAJARAN ---
    // Menampilkan Ruang Kelas (View: siswa.kelas.show)
    // Di sini nanti ada Tab: Materi, Tugas, Info
    Route::get('/kelas/{id}', [PageController::class, 'ruangKelas'])->name('kelas.show');

    // Menampilkan Preview Modul (View: siswa.materi.show)
    Route::get('/materi/{id}', [PageController::class, 'bacaMateri'])->name('materi.show');

    // --- TUGAS ---
    // Menampilkan Halaman Detail Tugas & Form Upload (View: siswa.tugas.show)
    Route::get('/tugas/{id}', [PageController::class, 'detailTugas'])->name('tugas.show');

    // --- NILAI & PROFILE ---
    // Menampilkan Halaman Riwayat Nilai (View: siswa.nilai.index)
    Route::get('/nilai', [PageController::class, 'riwayatNilai'])->name('nilai.index');
    
    // Menampilkan Halaman Edit Profile (View: siswa.profile.edit)
    Route::get('/profile', [PageController::class, 'editProfile'])->name('profile.edit');
});