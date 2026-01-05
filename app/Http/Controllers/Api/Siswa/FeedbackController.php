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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimoni = Testimoni::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return response()->json([
            'message' => 'Terima kasih! Feedback Anda berhasil dikirim.',
            'data' => $testimoni
        ], 201);
    }

    public function kirimKeluhan(Request $request)
    {
        $request->validate([
            'deskripsi_keluhan' => 'required|string',
            'bukti_keluhan' => 'nullable|image|max:2048',
        ]);

        $pathBukti = null;
        if ($request->hasFile('bukti_keluhan')) {
            $pathBukti = $request->file('bukti_keluhan')->store('bukti_keluhan');
        }

        $keluhan = KontakCs::create([
            'user_id' => Auth::id(),
            'deskripsi_keluhan' => $request->deskripsi_keluhan,
            'bukti_keluhan' => $pathBukti,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Keluhan Anda telah kami terima dan akan segera diproses.',
            'data' => $keluhan
        ], 201);
    }
}
