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
            //
              $table->string('periode')
                  ->nullable()
                  ->after('medali')
                  ->comment('Periode tahun atau semester kejuaraan');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kejuaraan_siswa', function (Blueprint $table) {
            //
        });
    }
};
