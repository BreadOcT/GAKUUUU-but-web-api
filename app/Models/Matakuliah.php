<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah'; // Singular name override

    protected $fillable = [
        'pengampu_id', // Relasi ke User (Tentor)
        'nama_mk',
        'deskripsi',
        'manfaat',
        'tujuan',
    ];

    // Relasi ke Tentor (User)
    public function pengampu()
    {
        return $this->belongsTo(User::class, 'pengampu_id');
    }

    // Relasi ke Kelas (One-to-Many)
    // Satu MK bisa punya banyak kelas (Kelas A, Kelas B, dll)
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}