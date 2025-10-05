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
        Schema::table('kejuaraan_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('kejuaraan_siswa', 'tingkat_kategori')) {
                $table->string('tingkat_kategori')->nullable()->after('tageuk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kejuaraan_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('kejuaraan_siswa', 'tingkat_kategori')) {
                $table->dropColumn('tingkat_kategori');
            }
        });
    }
};
