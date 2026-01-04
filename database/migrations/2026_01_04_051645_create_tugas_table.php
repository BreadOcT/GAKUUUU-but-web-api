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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id(); // String di ERD, tapi sebaiknya BigInt (default Laravel) untuk performa
            $table->foreignId('materi_id')->constrained('materi')->onDelete('cascade');
            $table->string('judul');
            $table->enum('jenis', ['kuis', 'tugas_harian', 'ujian']); 
            $table->text('deskripsi');
            $table->string('file_path')->nullable(); // Soal tugas
            $table->dateTime('deadline')->nullable(); // Tambahan penting untuk logika submit
            $table->timestamps(); // created_at
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
