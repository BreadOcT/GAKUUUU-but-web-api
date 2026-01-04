<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumpulan extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan';

    protected $fillable = [
        'tugas_id',
        'user_id',
        'file_jawaban',
        'nilai',
        'status', // 'menunggu_penilaian', 'dinilai', 'terlambat'
        'tanggal_selesai',
        'komentar_tentor',
    ];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}