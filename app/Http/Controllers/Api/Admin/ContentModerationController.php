<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class ContentModerationController extends Controller
{
    // List Semua Feedback
    public function index()
    {
        $feedbacks = Testimoni::with('user.userData')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($feedbacks);
    }

    // Approve Feedback
    public function approve($id)
    {
        $testimoni = Testimoni::find($id);
        if (!$testimoni) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $testimoni->update(['status' => 'approved']);
        
        return response()->json(['message' => 'Testimoni berhasil disetujui']);
    }

    // Hapus / Reject Feedback
    public function destroy($id)
    {
        $testimoni = Testimoni::find($id);
        if (!$testimoni) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $testimoni->delete();
        
        return response()->json(['message' => 'Testimoni berhasil dihapus']);
    }
}