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
        'role',
        'aktif', // <--- TAMBAHKAN INI (Wajib untuk Admin memverifikasi/memblokir user)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ... relasi userData, enrollments, matakuliahDiampu (biarkan) ...
    public function userData() { return $this->hasOne(UserData::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function pengumpulan() { return $this->hasMany(Pengumpulan::class); }
    public function matakuliahDiampu() { return $this->hasMany(Matakuliah::class, 'pengampu_id'); }
    public function testimoni() { return $this->hasMany(Testimoni::class); }
    public function kontakCs() { return $this->hasMany(KontakCs::class); }
}