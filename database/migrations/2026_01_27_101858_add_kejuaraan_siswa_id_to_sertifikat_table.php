<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->foreignId('kejuaraan_siswa_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('kejuaraan_siswa')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropForeign(['kejuaraan_siswa_id']);
            $table->dropColumn('kejuaraan_siswa_id');
        });
    }
};
