<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Siswa\PageController;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/login', [PageController::class, 'showLogin'])->name('login');
Route::get('/register', [PageController::class, 'showRegister'])->name('register');

Route::post('/login', [PageController::class, 'processLogin']);
Route::post('/register', [PageController::class, 'processRegister']);

Route::post('/logout', [PageController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth:web', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/katalog', [PageController::class, 'katalog'])->name('katalog.index');
    Route::get('/katalog/{id}', [PageController::class, 'detailMatakuliah'])->name('katalog.show');
    Route::post('/enrollment', [PageController::class, 'enrollMatakuliah'])->name('enrollment.store');
    Route::get('/kelas/{id}', [PageController::class, 'ruangKelas'])->name('kelas.show');
    Route::get('/materi/{id}', [PageController::class, 'bacaMateri'])->name('materi.show');
    Route::get('/tugas/{id}', [PageController::class, 'detailTugas'])->name('tugas.show');
    Route::post('/tugas/{id}/submit', [PageController::class, 'storeTugas'])->name('tugas.store');
    Route::get('/nilai', [PageController::class, 'riwayatNilai'])->name('nilai.index');
    Route::get('/profile', [PageController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [PageController::class, 'updateProfile'])->name('profile.update');
    Route::get('/feedback', [PageController::class, 'createFeedback'])->name('feedback.create');
    Route::post('/feedback', [PageController::class, 'storeFeedback'])->name('feedback.store');
    Route::get('/kontak-cs', [PageController::class, 'createKontakCs'])->name('kontak-cs.create');
    Route::post('/kontak-cs', [PageController::class, 'storeKontakCs'])->name('kontak-cs.store');

});
