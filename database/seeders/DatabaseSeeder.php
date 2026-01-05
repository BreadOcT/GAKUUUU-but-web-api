<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserData;
use App\Models\Matakuliah;
use App\Models\Kelas;
use App\Models\JadwalKelas;
use App\Models\Modul;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Enrollment;
use App\Models\Pengumpulan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $siswa = User::factory()->create([
            'username' => 'siswa1',
            'email' => 'siswa@gaku.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);
        UserData::factory()->create(['user_id' => $siswa->id, 'first_name' => 'Budi', 'last_name' => 'Santoso']);

        $tentor = User::factory()->create([
            'username' => 'tentor1',
            'email' => 'tentor@gaku.com',
            'password' => bcrypt('password'),
            'role' => 'tentor',
        ]);
        UserData::factory()->create(['user_id' => $tentor->id, 'first_name' => 'Dr. Siti', 'last_name' => 'AMINAH']);

        $matkuls = Matakuliah::factory(3)->create(['pengampu_id' => $tentor->id]);

        foreach ($matkuls as $mk) {
            $kelases = Kelas::factory(2)->create(['matakuliah_id' => $mk->id]);

            foreach ($kelases as $kelas) {
                JadwalKelas::factory()->create(['kelas_id' => $kelas->id]);
                
                $moduls = Modul::factory(3)->create(['kelas_id' => $kelas->id]);

                foreach ($moduls as $modul) {
                    $materis = Materi::factory(2)->create(['modul_id' => $modul->id]);

                    foreach ($materis as $materi) {
                        Tugas::factory()->create(['materi_id' => $materi->id]);
                    }
                }
            }
        }

        $kelasPertama = Kelas::first();
        
        Enrollment::create([
            'user_id' => $siswa->id,
            'kelas_id' => $kelasPertama->id,
            'status' => 'aktif',
            'semester' => 'Ganjil 2025',
            'tanggal_daftar' => now(),
        ]);

        $tugasAda = Tugas::whereHas('materi.modul.kelas', function($q) use ($kelasPertama) {
            $q->where('id', $kelasPertama->id);
        })->first();

        if ($tugasAda) {
            Pengumpulan::create([
                'tugas_id' => $tugasAda->id,
                'user_id' => $siswa->id,
                'file_jawaban' => 'dummy/file.pdf',
                'nilai' => 85.50,
                'status' => 'dinilai',
                'tanggal_selesai' => now()->subDays(2),
                'komentar_tentor' => 'Kerja bagus, pertahankan!',
            ]);
        }
    }
}
