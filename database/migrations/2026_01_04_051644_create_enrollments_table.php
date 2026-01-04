<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siswa
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('semester')->nullable(); // Misal: "Ganjil 2025"
            $table->enum('status', ['aktif', 'selesai', 'batal'])->default('aktif');
            $table->date('tanggal_daftar')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
