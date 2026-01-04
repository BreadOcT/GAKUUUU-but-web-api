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
        Schema::create('pengumpulan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siswa
            $table->string('file_jawaban')->nullable();
            $table->decimal('nilai', 5, 2)->nullable(); // Nilai (misal: 95.50), Nullable jika belum dinilai
            $table->enum('status', ['menunggu_penilaian', 'dinilai', 'terlambat'])->default('menunggu_penilaian');
            $table->dateTime('tanggal_selesai')->nullable(); // Waktu submit
            $table->text('komentar_tentor')->nullable(); // Feedback dari tentor (Sesuai Use Case 4.19)
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
