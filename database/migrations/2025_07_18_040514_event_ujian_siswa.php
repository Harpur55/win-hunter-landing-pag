<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_ujian_siswa', function (Blueprint $table) {
            $table->id();
    $table->foreignId('event_ujian_id')
          ->constrained('event_ujian') // ini yang diperbaiki
          ->onDelete('cascade');
    $table->foreignId('siswa_id')
          ->constrained('siswas') // pastikan nama tabel siswa sesuai
          ->onDelete('cascade');
    $table->string('current_belt_level')->nullable();
    $table->string('next_belt_level')->nullable();
    $table->string('keterangan')->nullable();
    $table->timestamps();

    $table->unique(['event_ujian_id', 'siswa_id']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_ujian_siswa');
    }
};


