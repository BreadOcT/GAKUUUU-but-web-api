<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Models\KontakCs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * FR-15 & Use Case 4.5: Pengisian Feedback/Testimoni
     * Siswa memberikan rating dan komentar terhadap layanan.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Sesuai Use Case 4.5.2)
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5', // Rating bintang 1-5
            'komentar' => 'required|string|max:1000',   // Ulasan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan ke Database
        $testimoni = Testimoni::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        // 3. Respon Sukses
        return response()->json([
            'message' => 'Terima kasih! Feedback Anda berhasil dikirim.',
            'data' => $testimoni
        ], 201);
    }

    /**
     * FR-17: Pengiriman Pesan Kontak (Layanan CS)
     * Siswa mengirim keluhan atau pertanyaan ke Admin.
     * (Tambahan untuk melengkapi fitur Utilitas)
     */
    public function kirimKeluhan(Request $request)
    {
        // Validasi
        $request->validate([
            'deskripsi_keluhan' => 'required|string',
            'bukti_keluhan' => 'nullable|image|max:2048', // Opsional, max 2MB
        ]);

        $pathBukti = null;
        if ($request->hasFile('bukti_keluhan')) {
            $pathBukti = $request->file('bukti_keluhan')->store('bukti_keluhan');
        }

        // Simpan ke tabel kontak_cs
        $keluhan = KontakCs::create([
            'user_id' => Auth::id(),
            'deskripsi_keluhan' => $request->deskripsi_keluhan,
            'bukti_keluhan' => $pathBukti,
            'status' => 'pending', // Default status
        ]);

        return response()->json([
            'message' => 'Keluhan Anda telah kami terima dan akan segera diproses.',
            'data' => $keluhan
        ], 201);
    }
}