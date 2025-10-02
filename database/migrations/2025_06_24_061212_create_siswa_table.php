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
            $table->string('no_register')->nullable()->unique(); // Nomor Registrasi, unik dan penting untuk identifikasi
            $table->string('nis')->unique(); // Nomor Induk Siswa, unik
            $table->string('nama_lengkap'); 
            $table->string('jenis_kelamin')->nullable(); ; 
            $table->string('tempat_lahir')->nullable(); ; 
            $table->date('tanggal_lahir')->nullable(); 
            $table->string('golongan_darah')->nullable(); 
            $table->string('image')->nullable(); 
            $table->longText('alamat_lengkap')->nullable(); 
            $table->longText('no_telepon')->nullable(); 

            // Informasi Orang Tua
            $table->longText('nama_ayah')->nullable(); // Nama Ayah
            $table->string('pekerjaan_ayah')->nullable(); // Pekerjaan Ayah
            $table->longText('nama_ibu')->nullable(); // Nama Ibu
            $table->string('pekerjaan_ibu')->nullable(); // Pekerjaan Ibu

            // Informasi Akademik/Pelatihan
            $table->foreignId('units_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('beladiri_yang_pernah_diikuti')->nullable();
            $table->string('current_belt_level')->default('Putih'); 
            // $table->string('next_belt_level')->nullable(); 
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
