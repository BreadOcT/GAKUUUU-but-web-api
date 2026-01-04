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
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->id();
            // pengampu_id merujuk ke users (role tentor)
            $table->foreignId('pengampu_id')->constrained('users')->onDelete('cascade'); 
            $table->string('nama_mk');
            $table->text('deskripsi');
            $table->text('manfaat')->nullable();
            $table->text('tujuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliahs');
    }
};
