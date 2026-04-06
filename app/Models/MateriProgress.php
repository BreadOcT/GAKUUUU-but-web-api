<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriProgress extends Model
{
    use HasFactory;

    protected $table = 'materi_progress';

    protected $fillable = [
        'user_id',
        'materi_id',
    ];
}