<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KontakCs;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_tentor' => User::where('role', 'tentor')->count(),
            'pending_tentor' => User::where('role', 'tentor')->where('aktif', false)->count(),
            'keluhan_pending' => KontakCs::where('status', 'pending')->count(),
            'testimoni_baru' => Testimoni::where('status', 'pending')->count(),
        ];

        return response()->json([
            'message' => 'Data statistik dashboard berhasil diambil',
            'data' => $stats
        ]);
    }
}