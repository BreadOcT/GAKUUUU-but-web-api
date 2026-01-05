<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifikasiTentorController extends Controller
{
    // List Tentor Pending
    public function index()
    {
        $tentors = User::where('role', 'tentor')
            ->where('aktif', false)
            ->with('userData')
            ->get();

        return response()->json(['data' => $tentors]);
    }

    // Detail Tentor (untuk cek dokumen)
    public function show($id)
    {
        $tentor = User::where('role', 'tentor')->with('userData')->find($id);

        if (!$tentor) {
            return response()->json(['message' => 'Data tentor tidak ditemukan'], 404);
        }

        return response()->json(['data' => $tentor]);
    }

    // Aksi Terima / Tolak
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:terima,tolak'
        ]);

        $tentor = User::where('role', 'tentor')->find($id);
        if (!$tentor) {
            return response()->json(['message' => 'Tentor tidak ditemukan'], 404);
        }

        if ($request->action == 'terima') {
            $tentor->aktif = true;
            $tentor->save();
            return response()->json(['message' => 'Tentor berhasil diverifikasi dan diaktifkan.']);
        } else {
            // Jika tolak, hapus akun
            $tentor->delete();
            return response()->json(['message' => 'Pengajuan ditolak. Akun dihapus.']);
        }
    }
}