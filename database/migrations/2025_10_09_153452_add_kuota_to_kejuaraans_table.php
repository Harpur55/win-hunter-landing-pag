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
        Schema::table('kejuaraans', function (Blueprint $table) {
            //
              $table->integer('kuota_reguler')->default(0);
            $table->integer('kuota_prestasi')->default(0);
            $table->integer('kuota_khusus')->default(0);
            $table->integer('kuota_kelas_poomsae')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kejuaraans', function (Blueprint $table) {
            //
        });
    }
};
