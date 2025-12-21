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
        //  $table->string('tempat_lahir')->nullable();
        // $table->date('tanggal_lahir')->nullable();
        // $table->string('no_register')->nullable();
        // $table->foreignId('units_id')->nullable()->constrained('units');
        // $table->foreignId('kelas_id')->nullable()->constrained('kelas');
        // $table->enum('jenis_kelamin', ['L', 'P'])->after('siswa_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_ujian_siswa', function (Blueprint $table) {
            //
            //  $table->dropColumn('tempat_lahir');
            //  $table->dropColumn('tanggal_lahir');
            //  $table->dropColumn('no_register');
            //  $table->dropForeign(['units_id']);
            //  $table->dropColumn('units_id');
            //  $table->dropForeign(['kelas_id']);
            //  $table->dropColumn('kelas_id');
            $table->dropColumn('jenis_kelamin');
        });
    }
};
