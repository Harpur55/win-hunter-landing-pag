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
              $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->string('no_register')->nullable();
        $table->foreignId('units_id')->nullable()->constrained('units');
        $table->foreignId('kelas_id')->nullable()->constrained('kelas');
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
