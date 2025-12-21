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
                    $table->string('hasil_ujian')->default('on proses'); // default status


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
