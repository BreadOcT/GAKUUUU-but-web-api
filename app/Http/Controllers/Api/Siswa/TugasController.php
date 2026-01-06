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
        $user = Auth::user();

        $daftarTugas = Tugas::whereHas('materi.modul', function ($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })
        ->with(['pengumpulan' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->orderBy('deadline', 'asc')
        ->get();

        $data = $daftarTugas->map(function ($tugas) {
            $pengumpulan = $tugas->pengumpulan->first();
            
            return [
                'id' => $tugas->id,
                'judul' => $tugas->judul,
                'deskripsi' => $tugas->deskripsi,
                'deadline' => $tugas->deadline ? $tugas->deadline->format('Y-m-d H:i:s') : null,
                'status_pengumpulan' => $pengumpulan ? $pengumpulan->status : 'belum',
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

    public function rekapNilai()
    {
        $nilai = Pengumpulan::where('user_id', Auth::id())
            ->with(['tugas.materi.modul.kelas.matakuliah'])
            ->get()
            ->map(function ($item) {
                return [
                    'matakuliah' => $item->tugas->materi->modul->kelas->matakuliah->nama_mk,
                    'tugas' => $item->tugas->judul,
                    'nilai' => $item->nilai ?? 'Belum Dinilai',
                    'komentar' => $item->komentar_tentor,
                    'status' => $item->status,
                ];
            });

        return response()->json(['data' => $nilai]);
    }
}
