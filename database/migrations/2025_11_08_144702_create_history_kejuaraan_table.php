<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_kejuaraan', function (Blueprint $table) {
            $table->id();

            // Relasi ke siswa dan kejuaraan (jika ingin)
            $table->foreignId('siswas_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kejuaraans_id')->nullable()->constrained('kejuaraans')->nullOnDelete();
            $table->string('nama_kejuaraan');
            $table->string('lokasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('kategori_pertandingan')->nullable();
            $table->string('medali')->nullable();
            $table->string('nama_peserta');
            $table->string('kategori_atlit')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_kejuaraan');
    }
};
