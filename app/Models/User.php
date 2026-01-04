<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',     // 'admin', 'tentor', 'siswa'
        'aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Data Diri (One-to-One)
    public function userData()
    {
        return $this->hasOne(UserData::class);
    }

    // Relasi Siswa: Enrollment (Kelas yang diambil)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi Siswa: Pengumpulan Tugas
    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class);
    }
    
    // Relasi Tentor: Mata Kuliah yang diampu
    public function matakuliahDiampu()
    {
        return $this->hasMany(Matakuliah::class, 'pengampu_id');
    }
}