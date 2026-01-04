<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'materi_id',
        'judul',
        'jenis', // 'kuis', 'tugas_harian', 'ujian'
        'deskripsi',
        'file_path', // File Soal
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime', // Agar otomatis jadi objek Carbon
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    // Relasi ke Jawaban Siswa
    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class);
    }
}