<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Pengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
public function index($kelasId)
    {
        $user = auth()->user();

        // Cari Tugas yang materinya ada di modul, dan modulnya ada di kelas ini
        $tugas = Tugas::whereHas('materi.modul', function ($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })
        // Eager load pengumpulan user ini untuk cek status
        ->with(['pengumpulan' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->orderBy('deadline', 'asc')
        ->get();

        // Format data agar mudah dibaca Flutter
        $data = $tugas->map(function ($item) {
            $pengumpulan = $item->pengumpulan->first();
            
            // Tentukan status
            $status = 'belum'; // Default
            if ($pengumpulan) {
                $status = $pengumpulan->nilai ? 'dinilai' : 'menunggu_penilaian';
            }

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'deadline' => $item->deadline, // Pastikan format datetime
                'status_pengumpulan' => $status,
                'nilai' => $pengumpulan ? $pengumpulan->nilai : null,
            ];
        });

        return response()->json([
            'message' => 'Daftar tugas berhasil diambil',
            'data' => $data
        ]);
    }
    
    public function submit(Request $request, $tugas_id)
    {
        $request->validate([
            'file_jawaban' => 'required|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $tugas = Tugas::findOrFail($tugas_id);

        if ($tugas->deadline && now()->greaterThan($tugas->deadline)) {
            return response()->json(['message' => 'Maaf, tenggat waktu pengumpulan telah berakhir.'], 400);
        }

        $path = $request->file('file_jawaban')->store('tugas_siswa');

        Pengumpulan::updateOrCreate(
            ['tugas_id' => $tugas_id, 'user_id' => Auth::id()],
            [
                'file_jawaban' => $path,
                'tanggal_selesai' => now(),
                'status' => 'menunggu_penilaian'
            ]
        );

        return response()->json(['message' => 'Tugas berhasil dikumpulkan'], 200);
    }

// FR-14: Cek Nilai (Riwayat)
    public function rekapNilai()
    {
        $user = Auth::user();

        // Ambil pengumpulan milik user yang SUDAH DINILAI (nilai tidak null)
        // Kita perlu relasi ke Tugas -> Materi -> Modul -> Kelas -> Matakuliah untuk dapat nama MK
        $riwayat = Pengumpulan::with(['tugas.materi.modul.kelas.matakuliah'])
            ->where('user_id', $user->id)
            ->whereNotNull('nilai') // Hanya yang sudah dinilai
            ->orderBy('updated_at', 'desc') // Yang baru dinilai di atas
            ->get();

        $data = $riwayat->map(function ($item) {
            // Navigasi data yang agak dalam untuk dapat nama Matkul
            // pengumpulan -> tugas -> materi -> modul -> kelas -> nama_mk (atau relasi matakuliah)
            
            $tugas = $item->tugas;
            $materi = $tugas->materi ?? null;
            $modul = $materi->modul ?? null;
            $kelas = $modul->kelas ?? null;
            
            // Logika ambil nama MK (sesuai struktur Model KelasModel kita sebelumnya)
            $namaMk = "Mata Kuliah Umum";
            if ($kelas) {
                if ($kelas->matakuliah) {
                    $namaMk = $kelas->matakuliah->nama_mk;
                } elseif ($kelas->nama_mk) {
                    $namaMk = $kelas->nama_mk;
                }
            }

            return [
                'id' => $item->id,
                'tugas_judul' => $tugas->judul,
                'mata_kuliah' => $namaMk,
                'nilai' => $item->nilai,
                'komentar' => $item->komentar_tentor ?? '-', // Komentar tentor
                'tanggal_dinilai' => $item->updated_at->format('d M Y'),
            ];
        });

        return response()->json([
            'message' => 'Data nilai berhasil diambil',
            'data' => $data
        ]);
    }
}
