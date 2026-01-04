<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $table = 'modul';

    protected $fillable = [
        'kelas_id',
        'judul',
        'deskripsi',
        'file_path', // File Modul Utama (PDF Buku Ajar dll)
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }
}