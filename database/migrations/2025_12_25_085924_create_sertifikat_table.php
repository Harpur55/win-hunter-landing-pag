<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();

            // 🔗 Relasi
            $table->foreignId('event_ujian_siswa_id')
                ->constrained('event_ujian_siswa')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswas')
                ->cascadeOnDelete();


            $table->string('no_sertifikat')->unique();
            $table->string('no_register');


            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->date('tanggal_ujian');


            $table->string('current_belt_level');
            $table->string('next_belt_level');

            $table->string('file_pdf');

            // 🧩 Metadata
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // ⚡ Index untuk performa
            $table->index(['siswa_id', 'event_ujian_siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
