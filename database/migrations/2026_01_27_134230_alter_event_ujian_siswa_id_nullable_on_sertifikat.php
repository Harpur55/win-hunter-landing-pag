<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
           
            $table->dropForeign(['event_ujian_siswa_id']);
            $table->foreignId('event_ujian_siswa_id')
                ->nullable()
                ->change();

            $table->foreign('event_ujian_siswa_id')
                ->references('id')
                ->on('event_ujian_siswa')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropForeign(['event_ujian_siswa_id']);

            $table->foreignId('event_ujian_siswa_id')
                ->nullable(false)
                ->change();

            $table->foreign('event_ujian_siswa_id')
                ->references('id')
                ->on('event_ujian_siswa')
                ->cascadeOnDelete();
        });
    }
};
