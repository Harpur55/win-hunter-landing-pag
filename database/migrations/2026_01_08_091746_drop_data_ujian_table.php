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
    Schema::dropIfExists('data_ujian');
}

public function down(): void
{
    Schema::create('data_ujian', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswas_id')->constrained('siswas')->cascadeOnDelete();
        $table->foreignId('units_id')->constrained('units')->cascadeOnDelete();
        $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
        $table->foreignId('event_ujian_id')->constrained('event_ujian')->cascadeOnDelete();
        $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}
};