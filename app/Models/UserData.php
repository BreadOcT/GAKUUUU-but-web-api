<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;

    protected $table = 'user_data'; // Definisi nama tabel eksplisit

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'no_telepon',
        'alamat_lengkap',
        'kota',
        'negara',
        'tanggal_lahir',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}