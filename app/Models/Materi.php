<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'modul_id',
        'judul',
        'deskripsi',
        'file_path',
        'tanggal_upload',
    ];

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    // Materi bisa memiliki Tugas
    public function tugas()
    {
        return $this->hasOne(Tugas::class);
    }
}