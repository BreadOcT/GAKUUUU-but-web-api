<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimoni;
use App\Models\KontakCs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    // 1. Simpan Testimoni / Feedback
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'required|string', // Database butuh 'komentar', bukan 'ulasan'
        ]);

        $user = Auth::user();

        // Cek apakah user sudah pernah review? (Opsional, kalau mau spam boleh di-skip)
        // Testimoni::where('user_id', $user->id)->delete(); 

        Testimoni::create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'status' => 'pending', // Default pending agar dimoderasi admin
        ]);

        return response()->json([
            'message' => 'Feedback berhasil dikirim',
        ], 201);
    }

    // 2. Simpan Keluhan CS
    public function kirimKeluhan(Request $request)
    {
        $request->validate([
            'deskripsi_keluhan' => 'required|string',
            'bukti_keluhan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();
        $path = null;

        if ($request->hasFile('bukti_keluhan')) {
            $path = $request->file('bukti_keluhan')->store('bukti_keluhan', 'public');
        }

        KontakCs::create([
            'user_id' => $user->id,
            'deskripsi_keluhan' => $request->deskripsi_keluhan,
            'bukti_keluhan' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim',
        ], 201);
    }
}