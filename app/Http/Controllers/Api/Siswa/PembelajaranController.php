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
    public function dashboard()
    {
        $userId = Auth::id();
        $enrollments = Enrollment::where('user_id', $userId)
            ->with(['kelas.matakuliah', 'kelas.jadwal', 'kelas.pengampu.userData'])
            ->get();

        return response()->json(['data' => $enrollments]);
    }

    public function show($kelas_id)
    {
        $isEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('kelas_id', $kelas_id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json(['message' => 'Akses ditolak. Anda belum terdaftar.'], 403);
        }

        $kelas = Kelas::with(['modul.materi'])->find($kelas_id);

        return response()->json(['data' => $kelas]);
    }

    public function downloadModul($modul_id)
    {
        $modul = Modul::findOrFail($modul_id);

        $isEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('kelas_id', $modul->kelas_id)
            ->exists();
        if (!$isEnrolled) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        if ($modul->file_path && Storage::exists($modul->file_path)) {
            return Storage::download($modul->file_path, $modul->judul);
        }

        return response()->json([
            'message' => 'File modul tidak tersedia atau belum diunggah oleh tentor.'
        ], 404);
    }
}
