<?php

namespace App\Http\Controllers\Web\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;     
use App\Models\User;                     
use App\Models\UserData;                 
use App\Models\Matakuliah;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Pengumpulan;
use App\Models\Testimoni;
use App\Models\KontakCs;
use App\Models\MateriProgress;
use Illuminate\Support\Facades\Storage;  

class PageController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

public function processLogin(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // 3. LOGIKA REDIRECT BERDASARKAN ROLE
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } 
            elseif ($user->role === 'siswa') {
                return redirect()->intended(route('siswa.dashboard'));
            }
            elseif ($user->role === 'tentor') {
                // Nanti kalau fitur tentor sudah dibuat:
                // return redirect()->intended(route('tentor.dashboard'));
                Auth::logout();
                return back()->withErrors(['email' => 'Fitur Tentor sedang dalam pengembangan.']);
            }

            // Jika role tidak dikenali
            Auth::logout();
            return back()->withErrors(['email' => 'Role akun tidak valid.']);
        }

        // 4. Jika password salah
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'aktif' => true,
        ]);

        UserData::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->input('last_name'),
        ]);

        Auth::login($user);

        return redirect()->route('siswa.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function dashboard(Request $request)
    {
        $userId = Auth::id();
        
        $query = Enrollment::where('user_id', $userId)
            ->where('status', 'aktif')
            ->with(['kelas.matakuliah', 'kelas.jadwal', 'kelas.pengampu.userData', 'kelas.modul.materi.tugas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kelas.matakuliah', function($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            if ($request->filter == 'terbaru') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->filter == 'terlama') {
                $query->orderBy('created_at', 'asc');
            }
        }

        $enrollments = $query->get();

        // Ambil SEMUA materi yang sudah selesai oleh user ini untuk dihitung
        $allCompletedMateriIds = MateriProgress::where('user_id', $userId)->pluck('materi_id')->toArray();

        foreach ($enrollments as $enrollment) {
            $totalMateri = 0;
            $totalTugas = 0;
            $materiSelesai = 0; 
            $tugasDisubmit = 0;

            foreach ($enrollment->kelas->modul as $modul) {
                $totalMateri += $modul->materi->count();
                
                foreach ($modul->materi as $materi) {
                    // Cek kelulusan materi
                    if (in_array($materi->id, $allCompletedMateriIds)) {
                        $materiSelesai++;
                    }

                    // Cek kelulusan tugas
                    if ($materi->tugas) {
                        $totalTugas++;
                        $hasSubmitted = \App\Models\Pengumpulan::where('user_id', $userId)
                                            ->where('tugas_id', $materi->tugas->id)
                                            ->exists();
                        if ($hasSubmitted) {
                            $tugasDisubmit++;
                        }
                    }
                }
            }

            $totalItem = $totalMateri + $totalTugas;
            $totalSelesai = $materiSelesai + $tugasDisubmit;

            $enrollment->completion_rate = $totalItem > 0 ? round(($totalSelesai / $totalItem) * 100) : 0;
        }

        return view('siswa.dashboard', compact('enrollments'));
    }

public function katalog(Request $request) // Tambahkan parameter Request
    {
        // 1. Setup Query Dasar
        $query = Matakuliah::with('pengampu.userData');

        // 2. Fitur Pencarian (Search by Nama Mata Kuliah)
        if ($request->filled('search')) {
            $search = $request->search;
            // Filter berdasarkan kolom nama_mk di tabel matakuliah
            $query->where('nama_mk', 'like', "%{$search}%");
        }

        // 3. Fitur Filter (Pengurutan)
        if ($request->filled('filter')) {
            if ($request->filter == 'terbaru') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->filter == 'terlama') {
                $query->orderBy('created_at', 'asc');
            } elseif ($request->filter == 'abjad_a_z') {
                $query->orderBy('nama_mk', 'asc');
            } elseif ($request->filter == 'abjad_z_a') {
                $query->orderBy('nama_mk', 'desc');
            }
        } else {
            // Urutan default jika tidak ada filter (opsional)
            $query->orderBy('created_at', 'desc');
        }

        // Eksekusi query
        $matakuliah = $query->get();
        
        return view('siswa.katalog.index', compact('matakuliah'));
    }

    public function detailMatakuliah($id)
    {
        $matakuliah = Matakuliah::with(['kelas.jadwal', 'pengampu.userData'])->findOrFail($id);
        
        return view('siswa.katalog.show', compact('matakuliah'));
    }

    public function ruangKelas($id)
    {
        $userId = Auth::id();

        $isEnrolled = Enrollment::where('user_id', $userId)->where('kelas_id', $id)->exists();
        if (!$isEnrolled) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $kelas = Kelas::with(['modul.materi.tugas', 'matakuliah', 'jadwal'])->findOrFail($id);
        
        // AMBIL DATA MATERI YANG SUDAH SELESAI
        $completedMateriIds = MateriProgress::where('user_id', $userId)->pluck('materi_id')->toArray();

        // Lempar variabel $completedMateriIds ke view
        return view('siswa.kelas.show', compact('kelas', 'completedMateriIds'));
    }

    public function bacaMateri($id)
    {
        $materi = Materi::with('modul.kelas.matakuliah')->findOrFail($id);

        return view('siswa.kelas.materi', compact('materi'));
    }

    public function detailTugas($id)
    {
        $tugas = Tugas::with('materi.modul.kelas')->findOrFail($id);
        
        $submission = Pengumpulan::where('user_id', Auth::id())
            ->where('tugas_id', $id)
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'submission'));
    }

    public function riwayatNilai()
    {
        $userId = Auth::id();

        $nilai = Pengumpulan::where('user_id', $userId)
            ->with(['tugas.materi.modul.kelas.matakuliah', 'tugas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.nilai.index', compact('nilai'));
    }

    public function storeTugas(Request $request, $id)
    {

        $request->validate([
            'file_jawaban' => 'required|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $user = Auth::user();
        $tugas = Tugas::findOrFail($id);


        if ($tugas->deadline && now()->greaterThan($tugas->deadline)) {
            return back()->with('error', 'Maaf, tenggat waktu pengumpulan telah berakhir.');
        }

        $path = $request->file('file_jawaban')->store('tugas_siswa', 'public');

        Pengumpulan::updateOrCreate(
            [
                'tugas_id' => $id,
                'user_id' => $user->id
            ],
            [
                'file_jawaban' => $path,
                'tanggal_selesai' => now(),
                'status' => 'menunggu_penilaian' 
            ]
        );

        return redirect()->route('siswa.tugas.show', $id)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function editProfile()
    {
        $user = Auth::user()->load('userData');
        return view('siswa.utilitas.profile', compact('user'));
    }

    public function createFeedback()
    {
        return view('siswa.utilitas.feedback');
    }

    public function createKontakCs()
    {
        return view('siswa.utilitas.kontak_cs');
    }

    public function enrollMatakuliah(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = Auth::user();

        $exists = Enrollment::where('user_id', $user->id)
            ->where('kelas_id', $request->kelas_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar di kelas ini sebelumnya.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'kelas_id' => $request->kelas_id,
            'status' => 'aktif',
            'semester' => 'Ganjil 2025',
            'tanggal_daftar' => now(),
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Berhasil mendaftar kelas! Selamat belajar.');
    }

    public function storeKontakCs(Request $request)
    {
        $request->validate([
            'deskripsi_keluhan' => 'required|string|max:1000',
            'bukti_keluhan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pathBukti = null;

        if ($request->hasFile('bukti_keluhan')) {
            $pathBukti = $request->file('bukti_keluhan')->store('bukti_keluhan', 'public');
        }

        KontakCs::create([
            'user_id' => Auth::id(),
            'deskripsi_keluhan' => $request->deskripsi_keluhan,
            'bukti_keluhan' => $pathBukti,
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.kontak-cs.create')
            ->with('success', 'Laporan Anda berhasil dikirim. Tim kami akan segera merespons.');
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        Testimoni::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('siswa.feedback.create')
            ->with('success', 'Terima kasih! Feedback Anda sangat berarti bagi kami.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userData = $user->userData;

        if (!$userData) {
            $userData = new \App\Models\UserData();
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

        return redirect()->route('siswa.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
    public function markMateriDone($id)
    {
        // Menyimpan data bahwa siswa ini sudah menyelesaikan materi ini
        MateriProgress::firstOrCreate([
            'user_id' => Auth::id(),
            'materi_id' => $id
        ]);

        return back()->with('success', 'Materi berhasil ditandai selesai!');
    }
}
