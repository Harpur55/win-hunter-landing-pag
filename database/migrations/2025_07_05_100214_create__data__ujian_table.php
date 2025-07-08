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
        Schema::create('data_ujian', function (Blueprint $table) {
             $table->id();
            $table->foreignId('siswas_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('units_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); 
            $table->foreignId('event_ujian_id')->constrained('event_ujian')->onDelete('cascade'); // Mengoreksi ke tabel 'kelas'
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            // Kolom tingkat_sabuk_saat_ini dan tingkat_sabuk_berikutnya dihapus karena ada di tabel 'siswas'
            $table->text('keterangan')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_data__ujian');
    }
};
