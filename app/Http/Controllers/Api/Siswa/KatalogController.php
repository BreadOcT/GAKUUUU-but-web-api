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
    public function index()
    {
        $matakuliah = Matakuliah::with('pengampu.userData')->get();
        return response()->json(['data' => $matakuliah]);
    }

    public function show($id)
    {
        $matakuliah = Matakuliah::with(['kelas.jadwal', 'pengampu.userData'])->find($id);

        if (!$matakuliah) {
            return response()->json(['message' => 'Mata kuliah tidak ditemukan'], 404);
        }

        return response()->json(['data' => $matakuliah]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = Auth::user();

        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('kelas_id', $request->kelas_id)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Anda sudah terdaftar di kelas ini'], 409);
        }

        Enrollment::create([
            'user_id' => $user->id,
            'kelas_id' => $request->kelas_id,
            'status' => 'aktif',
            'semester' => 'Ganjil 2025',
            'tanggal_daftar' => now(),
        ]);

        return response()->json(['message' => 'Berhasil mendaftar mata kuliah'], 201);
    }
}
