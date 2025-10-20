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
        Schema::table('event_ujian', function (Blueprint $table) {
            //
            $table->boolean('is_registration_closed')->default(false)->after('tanggal_ujian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_ujian', function (Blueprint $table) {
            //
        });
    }
};
