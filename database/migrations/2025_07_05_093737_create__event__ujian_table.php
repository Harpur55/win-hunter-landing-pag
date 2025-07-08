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
        Schema::create('event_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->date('tanggal_ujian');
            $table->string('lokasi_ujian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_event__ujian');
    }
};
