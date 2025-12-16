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
        Schema::table('event_ujian_siswa', function (Blueprint $table) {
            //
               $table->string('nama_lengkap')->after('event_ujian_id');
            $table->string('tempat_lahir')->after('nama_lengkap');
            $table->date('tanggal_lahir')->after('tempat_lahir');
            $table->string('no_register')->after('tanggal_lahir');

            // RELASI UNIT
            $table->foreignId('units_id')
                  ->after('no_register')
                  ->constrained('units')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_ujian_siswa', function (Blueprint $table) {
            //
        });
    }
};
