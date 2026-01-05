<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    // List Semua User
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->with('userData')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($users);
    }

    // Detail User
    public function show($id)
    {
        $user = User::with('userData')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json(['data' => $user]);
    }

    // Update / Edit User (Termasuk Ban)
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status_akun' => 'required|boolean' // true = aktif, false = banned
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update User
        $user->email = $request->email;
        $user->aktif = $request->status_akun;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update Profile
        $user->userData()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'no_telepon' => $request->no_telepon
            ]
        );

        return response()->json([
            'message' => 'Data user berhasil diperbarui',
            'data' => $user->load('userData')
        ]);
    }

    // Hapus User
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus permanen']);
    }
}