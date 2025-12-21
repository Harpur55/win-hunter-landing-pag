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
            $table->enum('grades', [
                'nasional_A',
                'nasional_B',
                'daerah_A', 
                'daerah_B',
                'tryout_antar_club',
            ])->default('daerah_B')->after('nama_kejuaraan');
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
