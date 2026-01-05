<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserData;
use App\Models\Testimoni;
use App\Models\KontakCs;
use Illuminate\Support\Facades\Hash;

class AdminPageController extends Controller
{
    // --- 1. DASHBOARD ---
    public function dashboard()
    {
        // Mengambil statistik untuk widget dashboard
        $stats = [
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_tentor' => User::where('role', 'tentor')->count(),
            'pending_tentor' => User::where('role', 'tentor')->where('aktif', false)->count(),
            'keluhan_pending' => KontakCs::where('status', 'pending')->count(),
            'testimoni_baru' => Testimoni::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // --- 2. MANAJEMEN USER (UC-4.11) ---
    
    public function usersIndex()
    {
        // Ambil semua user kecuali admin, paginate 10 per halaman
        $users = User::where('role', '!=', 'admin')
            ->with('userData')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function usersEdit($id)
    {
        $user = User::with('userData')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8', // Password opsional (diisi hanya jika ingin reset)
            'status_akun' => 'required|boolean' // Untuk ban/unban user
        ]);

        // Update Data Dasar
        $user->email = $request->email;
        $user->aktif = $request->status_akun;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update UserData
        $user->userData()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'no_telepon' => $request->no_telepon
            ]
        );

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus user (Cascade akan menghapus userData, enrollment, dll)
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // --- 3. VERIFIKASI TENTOR (UC-4.14) ---

    public function verifikasiIndex()
    {
        // Hanya tampilkan tentor yang belum aktif (pending)
        $tentors = User::where('role', 'tentor')
            ->where('aktif', false) // Asumsi: aktif = false berarti belum diverifikasi
            ->with('userData')
            ->get();

        return view('admin.verifikasi.index', compact('tentors'));
    }

    public function verifikasiShow($id)
    {
        $tentor = User::where('role', 'tentor')->with('userData')->findOrFail($id);
        return view('admin.verifikasi.show', compact('tentor'));
    }

    public function verifikasiProcess(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:terima,tolak'
        ]);

        $tentor = User::findOrFail($id);

        if ($request->action == 'terima') {
            $tentor->aktif = true;
            $tentor->save();
            $message = 'Tentor berhasil diverifikasi dan diaktifkan.';
        } else {
            // Jika ditolak, bisa dihapus atau dibiarkan non-aktif
            // Di sini kita pilih hapus agar mereka daftar ulang dengan data benar
            $tentor->delete(); 
            $message = 'Pengajuan tentor ditolak dan data dihapus.';
        }

        return redirect()->route('admin.verifikasi.index')->with('success', $message);
    }

    // --- 4. MODERASI FEEDBACK (UC-4.12) ---

    public function feedbackIndex()
    {
        // Tampilkan testimoni pending di atas
        $feedbacks = Testimoni::with(['user.userData'])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function feedbackApprove($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->update(['status' => 'approved']);

        return back()->with('success', 'Testimoni berhasil disetujui dan tampil ke publik.');
    }

    public function feedbackDestroy($id)
    {
        // Bisa delete permanen atau set status 'rejected'
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }

    // --- 5. CUSTOMER SERVICE (UC-4.13) ---

    public function csIndex()
    {
        // Urutkan pending paling atas
        $keluhan = KontakCs::with(['user.userData'])
            ->orderByRaw("FIELD(status, 'pending', 'diproses', 'selesai')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.cs.index', compact('keluhan'));
    }

    public function csShow($id)
    {
        $keluhan = KontakCs::with(['user.userData'])->findOrFail($id);
        
        // Jika status masih pending, ubah jadi diproses saat admin membuka
        if ($keluhan->status == 'pending') {
            $keluhan->update(['status' => 'diproses']);
        }

        return view('admin.cs.show', compact('keluhan'));
    }

    public function csReply(Request $request, $id)
    {
        $request->validate([
            'balasan_admin' => 'required|string'
        ]);

        $keluhan = KontakCs::findOrFail($id);
        
        $keluhan->update([
            'balasan_admin' => $request->balasan_admin,
            'status' => 'selesai' // Tandai selesai setelah dibalas
        ]);

        return redirect()->route('admin.cs.index')->with('success', 'Balasan berhasil dikirim.');
    }
}