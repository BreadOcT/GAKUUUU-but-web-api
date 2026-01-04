<?php

namespace App\Http\Controllers\Web\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Matakuliah;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Pengumpulan;

class PageController extends Controller
{
    // --- AUTHENTICATION PAGES ---
    
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // --- DASHBOARD & UTAMA ---

    /**
     * Menampilkan Dashboard Siswa (Mata Kuliah Saya).
     * Use Case: Dashboard Siswa
     */
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Ambil data enrollment siswa yang aktif
        // Eager load: kelas, matakuliah, dan jadwal agar query efisien
        $enrollments = Enrollment::where('user_id', $userId)
            ->where('status', 'aktif')
            ->with(['kelas.matakuliah', 'kelas.jadwal', 'kelas.pengampu.userData'])
            ->get();

        return view('siswa.dashboard', compact('enrollments'));
    }

    // --- KATALOG & PENDAFTARAN ---

    /**
     * Menampilkan daftar semua mata kuliah yang tersedia.
     * Use Case: 4.6 Pilih Mata Kuliah
     */
    public function katalog()
    {
        // Ambil semua MK beserta data pengampunya
        $matakuliah = Matakuliah::with('pengampu.userData')->get();
        
        return view('siswa.katalog.index', compact('matakuliah'));
    }

    /**
     * Menampilkan detail MK dan pilihan kelas/tentor.
     * Use Case: 4.8 Pilih Tentor & 4.4 Pilih Jadwal
     */
    public function detailMatakuliah($id)
    {
        $matakuliah = Matakuliah::with(['kelas.jadwal', 'pengampu.userData'])->findOrFail($id);
        
        return view('siswa.katalog.show', compact('matakuliah'));
    }

    // --- RUANG KELAS & PEMBELAJARAN ---

    /**
     * Halaman utama kelas (LMS) tempat siswa belajar.
     * Berisi Tab: Modul, Materi, Tugas.
     * Use Case: 4.7 Akses Modul
     */
    public function ruangKelas($id)
    {
        $userId = Auth::id();

        // 1. Keamanan: Cek apakah siswa benar-benar terdaftar di kelas ini?
        $isEnrolled = Enrollment::where('user_id', $userId)
            ->where('kelas_id', $id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        // 2. Ambil data Kelas beserta Modul, Materi, dan Tugas
        $kelas = Kelas::with(['modul.materi', 'matakuliah', 'jadwal'])->findOrFail($id);
        
        // 3. Ambil daftar tugas di kelas ini untuk ditampilkan di sidebar/tab tugas
        // Kita cari tugas berdasarkan materi yang ada di modul kelas ini
        $tugasList = Tugas::whereHas('materi.modul', function($q) use ($id) {
            $q->where('kelas_id', $id);
        })->get();

        return view('siswa.kelas.show', compact('kelas', 'tugasList'));
    }

    /**
     * Menampilkan detail materi pembelajaran (Video/PDF viewer).
     */
    public function bacaMateri($id)
    {
        // Pastikan materi ada
        $materi = Materi::with('modul.kelas.matakuliah')->findOrFail($id);

        // (Opsional) Tambahkan logika cek enrollment lagi di sini untuk keamanan ganda

        return view('siswa.kelas.materi', compact('materi'));
    }

    // --- TUGAS & PENGUMPULAN ---

    /**
     * Menampilkan detail soal tugas dan form upload.
     * Use Case: 4.9 Upload tugas dan kuis
     */
    public function detailTugas($id)
    {
        $tugas = Tugas::with('materi.modul.kelas')->findOrFail($id);
        
        // Cek apakah siswa sudah pernah mengumpulkan sebelumnya?
        $submission = Pengumpulan::where('user_id', Auth::id())
            ->where('tugas_id', $id)
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'submission'));
    }

    // --- NILAI & PROFILE ---

    /**
     * Menampilkan rekapitulasi nilai siswa (Transkrip).
     * Use Case: 4.10 Cek Nilai
     */
    public function riwayatNilai()
    {
        $userId = Auth::id();

        // Ambil semua pengumpulan tugas user ini
        $nilai = Pengumpulan::where('user_id', $userId)
            ->with(['tugas.materi.modul.kelas.matakuliah', 'tugas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.nilai.index', compact('nilai'));
    }

    /**
     * Menampilkan form edit profil.
     */
    public function editProfile()
    {
        $user = Auth::user()->load('userData');
        return view('siswa.utilitas.profile', compact('user'));
    }

    /**
     * Memproses Logout User (Web).
     * Menghapus sesi, regenerasi token CSRF, dan redirect ke halaman login.
     */
    public function logout(Request $request)
    {
        // 1. Proses Logout via Auth Facade
        Auth::logout();
 
        // 2. Invalidate Session (Agar sesi lama tidak bisa dipakai lagi)
        $request->session()->invalidate();
 
        // 3. Regenerate Token (Mencegah serangan CSRF)
        $request->session()->regenerateToken();
 
        // 4. Redirect kembali ke halaman login
        return redirect('/login');
    }
}