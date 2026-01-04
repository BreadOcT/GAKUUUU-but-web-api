<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use App\Models\Kelas;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    // Menampilkan daftar semua MK
    public function index()
    {
        // Load relasi pengampu (user data) agar nama tentor muncul
        $matakuliah = Matakuliah::with('pengampu.userData')->get();
        return response()->json(['data' => $matakuliah]);
    }

    // Menampilkan detail MK beserta Kelas/Jadwal yang tersedia (UC-4.8 Pilih Tentor)
    public function show($id)
    {
        $matakuliah = Matakuliah::with(['kelas.jadwal', 'pengampu.userData'])->find($id);

        if (!$matakuliah) {
            return response()->json(['message' => 'Mata kuliah tidak ditemukan'], 404);
        }

        return response()->json(['data' => $matakuliah]);
    }

    // FR-06: Proses Pendaftaran (Enrollment)
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = Auth::user();

        // Cek apakah siswa sudah terdaftar di kelas ini sebelumnya
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('kelas_id', $request->kelas_id)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Anda sudah terdaftar di kelas ini'], 409);
        }

        // Simpan data pendaftaran
        Enrollment::create([
            'user_id' => $user->id,
            'kelas_id' => $request->kelas_id,
            'status' => 'aktif',
            'semester' => 'Ganjil 2025', // Bisa dibuat dinamis
            'tanggal_daftar' => now(),
        ]);

        return response()->json(['message' => 'Berhasil mendaftar mata kuliah'], 201);
    }
}