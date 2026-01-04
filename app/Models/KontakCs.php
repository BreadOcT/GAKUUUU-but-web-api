<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontakCs extends Model
{
    use HasFactory;

    protected $table = 'kontak_cs';

    protected $fillable = [
        'user_id',
        'deskripsi_keluhan',
        'bukti_keluhan',
        'status',
        'balasan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}