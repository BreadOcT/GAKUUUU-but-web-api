<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('materi_id')->constrained('materi')->cascadeOnDelete();
            $table->timestamps();
            
            // Mencegah 1 user klik "done" berkali-kali di materi yang sama
            $table->unique(['user_id', 'materi_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_progress');
    }
};