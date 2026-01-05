<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakCs;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    // List Pesan Masuk
    public function index()
    {
        $keluhan = KontakCs::with('user.userData')
            ->orderByRaw("FIELD(status, 'pending', 'diproses', 'selesai')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($keluhan);
    }

    // Detail Pesan
    public function show($id)
    {
        $keluhan = KontakCs::with('user.userData')->find($id);
        
        if (!$keluhan) {
            return response()->json(['message' => 'Pesan tidak ditemukan'], 404);
        }

        // Tandai sedang diproses jika admin membuka detail
        if ($keluhan->status == 'pending') {
            $keluhan->update(['status' => 'diproses']);
        }

        return response()->json(['data' => $keluhan]);
    }

    // Kirim Balasan Admin
    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan_admin' => 'required|string'
        ]);

        $keluhan = KontakCs::find($id);
        if (!$keluhan) {
            return response()->json(['message' => 'Pesan tidak ditemukan'], 404);
        }

        $keluhan->update([
            'balasan_admin' => $request->balasan_admin,
            'status' => 'selesai'
        ]);

        return response()->json([
            'message' => 'Balasan terkirim',
            'data' => $keluhan
        ]);
    }
}