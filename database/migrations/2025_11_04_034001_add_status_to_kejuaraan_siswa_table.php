<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kejuaraan_siswa', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'selesai', 'batal'])
                ->default('aktif')
                ->after('tinggi_badan');
        });
    }

    public function down(): void
    {
        Schema::table('kejuaraan_siswa', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
