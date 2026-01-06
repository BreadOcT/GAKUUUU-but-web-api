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
public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        // Ambil kelas dimana siswa terdaftar (Enrollment)
        // PENTING: with('kelas.matakuliah.pengampu.userData')
        $enrollments = Enrollment::with(['kelas.matakuliah.pengampu.userData'])
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->get();

        // Kita map agar strukturnya langsung jadi list Kelas
        $dataKelas = $enrollments->map(function($enrollment) {
            $kelas = $enrollment->kelas;
            // Hitung modul manual jika withCount belum jalan
            $kelas->modul_count = $kelas->modul()->count(); 
            return $kelas;
        });

        return response()->json([
            'message' => 'Dashboard data',
            'data' => $dataKelas
        ]);
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
