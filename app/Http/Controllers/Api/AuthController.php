<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // FR-02: Registrasi Siswa
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 1. Buat Akun User
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa', // Default role siswa
            'aktif' => true,   // Bisa diset false jika butuh verifikasi email
        ]);

        // 2. Buat Data Profil (Tabel user_data)
        UserData::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name ?? null,
        ]);

        // 3. Buat Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // FR-01: Login Pengguna
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userData = $user->userData;
        
        if (!$userData) {
            $userData = new UserData();
            $userData->user_id = $user->id;
        }

        if ($request->hasFile('foto_profil')) {
            if ($userData->foto_profil && Storage::disk('public')->exists($userData->foto_profil)) {
                Storage::disk('public')->delete($userData->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profil', 'public');
            $userData->foto_profil = $path;
        }

        $userData->first_name = $request->first_name;
        $userData->last_name = $request->last_name;
        $userData->no_telepon = $request->no_telepon;
        $userData->alamat_lengkap = $request->alamat_lengkap;
        
        $userData->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->load('userData')
        ]);
    }
}