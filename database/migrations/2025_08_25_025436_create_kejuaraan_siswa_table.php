<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('kejuaraan_siswa', function (Blueprint $table) {
    $table->id();

    $table->foreignId('kejuaraan_id')->constrained('kejuaraans')->cascadeOnDelete();
    $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
    $table->string('nama_lengkap');
    $table->string('tempat_lahir')->nullable();
    $table->date('tanggal_lahir')->nullable();
    $table->enum('jenis_kelamin', ['L', 'P']); 
    $table->string('sabuk');
    $table->enum('kategori_pertandingan',['kyorugi','poomsae'])->nullable();
    $table->enum('tageuk', ['1', '2', '3', '4', '5', '6', '7', '8'])->nullable();
    $table->enum('kategori_atlit',['pracadet','cadet','junior','senior'])->nullable();
    $table->integer('berat_badan')->nullable();
    $table->integer('tinggi_badan')->nullable();
    $table->enum('medali', ['tidak_ada', 'emas', 'perak', 'perunggu'])->default('tidak_ada');

    $table->timestamps();
});

 }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kejuaraan_siswa');
    }
};
