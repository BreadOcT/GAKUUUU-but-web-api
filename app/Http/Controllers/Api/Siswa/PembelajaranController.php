<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembelajaranController extends Controller
{
    // Dashboard: List MK yang SEDANG diambil siswa
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Ambil enrollment user, include data kelas, matakuliah, dan jadwal
        $enrollments = Enrollment::where('user_id', $userId)
            ->with(['kelas.matakuliah', 'kelas.jadwal', 'kelas.pengampu.userData'])
            ->get();

        return response()->json(['data' => $enrollments]);
    }

    // Masuk ke dalam satu kelas (Lihat Modul & Materi)
    public function show($kelas_id)
    {
        // Validasi: Pastikan siswa benar-benar terdaftar di kelas ini
        $isEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('kelas_id', $kelas_id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json(['message' => 'Akses ditolak. Anda belum terdaftar.'], 403);
        }

        // Ambil data kelas beserta modul dan materinya
        $kelas = Kelas::with(['modul.materi'])->find($kelas_id);

        return response()->json(['data' => $kelas]);
    }

    // FR-10: Download Modul
    public function downloadModul($modul_id)
    {
        $modul = Modul::findOrFail($modul_id);
        
        // Cek logic akses (apakah siswa enrolled di kelas modul ini)
        // ... (Logic cek enrollment sama seperti di atas)

        if (Storage::exists($modul->file_path)) {
            return Storage::download($modul->file_path, $modul->judul);
        }

        return response()->json(['message' => 'File tidak ditemukan'], 404);
    }
}