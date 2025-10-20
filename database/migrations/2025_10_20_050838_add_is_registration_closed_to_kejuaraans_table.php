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
                    $table->boolean('is_registration_closed')->default(false)->after('tanggal_selesai');

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
