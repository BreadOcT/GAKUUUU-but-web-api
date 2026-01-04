<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'matakuliah_id',
        'nama_kelas',
        'deskripsi',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    // Relasi ke Jadwal (One-to-Many)
    public function jadwal()
    {
        return $this->hasMany(JadwalKelas::class);
    }

    // Relasi ke Modul (Materi Pembelajaran)
    public function modul()
    {
        return $this->hasMany(Modul::class);
    }
    
    // Shortcut untuk mengakses Pengampu (Tentor) lewat Matakuliah
    public function pengampu()
    {
        return $this->hasOneThrough(User::class, Matakuliah::class, 'id', 'id', 'matakuliah_id', 'pengampu_id');
    }
}