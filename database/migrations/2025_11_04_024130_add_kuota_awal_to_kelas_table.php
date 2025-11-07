<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Tambah kolom kuota_awal setelah kolom kuota (opsional bisa diganti posisi)
            $table->integer('kuota_awal')->default(0)->after('kuota');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('kuota_awal');
        });
    }
};
