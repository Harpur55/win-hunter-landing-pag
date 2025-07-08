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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('no_register')->unique(); // Nomor Registrasi, unik dan penting untuk identifikasi
            $table->string('nis')->unique(); // Nomor Induk Siswa, unik
            $table->string('nama_lengkap'); 
            $table->string('jenis_kelamin'); 
            $table->string('tempat_lahir'); 
            $table->date('tanggal_lahir'); 
            $table->string('golongan_darah')->nullable(); 
            $table->string('image')->nullable(); 
            $table->string('alamat_lengkap')->nullable(); 
            $table->string('no_telepon')->nullable(); 

            // Informasi Orang Tua
            $table->string('nama_ayah')->nullable(); // Nama Ayah
            $table->string('pekerjaan_ayah')->nullable(); // Pekerjaan Ayah
            $table->string('nama_ibu')->nullable(); // Nama Ibu
            $table->string('pekerjaan_ibu')->nullable(); // Pekerjaan Ibu

            // Informasi Akademik/Pelatihan
            $table->string('unit_latihan'); 
            $table->string('kelas'); 
            $table->string('current_belt_level')->default('Putih'); 
            $table->string('next_belt_level')->nullable(); 
            $table->date('joint_date')->nullable(); 

            $table->string('status')->default('Aktif'); 
            $table->timestamps(); 
                    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
