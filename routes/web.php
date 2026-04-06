<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Siswa\PageController;
use App\Http\Controllers\Web\Admin\AdminPageController;

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
    Route::post('/materi/{id}/mark-done', [PageController::class, 'markMateriDone'])->name('materi.mark_done');

});

Route::middleware(['auth:web', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- DASHBOARD ---
    // View: admin.dashboard
    Route::get('/dashboard', [AdminPageController::class, 'dashboard'])->name('dashboard');

    // --- MANAJEMEN USER (CRUD) ---
    // View: admin.users.index (Tabel User)
    Route::get('/users', [AdminPageController::class, 'usersIndex'])->name('users.index');
    // View: admin.users.edit (Form Edit)
    Route::get('/users/{id}/edit', [AdminPageController::class, 'usersEdit'])->name('users.edit');
    // Proses Update (POST/PUT)
    Route::put('/users/{id}', [AdminPageController::class, 'usersUpdate'])->name('users.update');
    // Proses Delete
    Route::delete('/users/{id}', [AdminPageController::class, 'usersDestroy'])->name('users.destroy');

    // --- VERIFIKASI TENTOR ---
    // View: admin.verifikasi.index (Tabel Pending)
    Route::get('/verifikasi', [AdminPageController::class, 'verifikasiIndex'])->name('verifikasi.index');
    // View: admin.verifikasi.show (Detail Dokumen)
    Route::get('/verifikasi/{id}', [AdminPageController::class, 'verifikasiShow'])->name('verifikasi.show');
    // Proses Verifikasi (Terima/Tolak)
    Route::post('/verifikasi/{id}', [AdminPageController::class, 'verifikasiProcess'])->name('verifikasi.process');

    // --- MODERASI FEEDBACK ---
    // View: admin.feedback.index (Tabel Feedback)
    Route::get('/feedback', [AdminPageController::class, 'feedbackIndex'])->name('feedback.index');
    // Proses Approve
    Route::post('/feedback/{id}/approve', [AdminPageController::class, 'feedbackApprove'])->name('feedback.approve');
    // Proses Hapus/Reject
    Route::delete('/feedback/{id}', [AdminPageController::class, 'feedbackDestroy'])->name('feedback.destroy');

    // --- CUSTOMER SERVICE (CS) ---
    // View: admin.cs.index (Inbox Pesan)
    Route::get('/cs', [AdminPageController::class, 'csIndex'])->name('cs.index');
    // View: admin.cs.show (Baca Detail & Form Balas)
    Route::get('/cs/{id}', [AdminPageController::class, 'csShow'])->name('cs.show');
    // Proses Kirim Balasan
    Route::post('/cs/{id}/reply', [AdminPageController::class, 'csReply'])->name('cs.reply');
});
