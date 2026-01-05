<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Pengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
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
