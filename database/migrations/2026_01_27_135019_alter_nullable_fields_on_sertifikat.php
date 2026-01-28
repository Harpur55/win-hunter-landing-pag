<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {

            $table->string('no_sertifikat')->nullable()->change();
            $table->string('no_register')->nullable()->change();

            // optional tapi aman
            $table->date('tanggal_ujian')->nullable()->change();
            $table->string('current_belt_level')->nullable()->change();
            $table->string('next_belt_level')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {

            $table->string('no_sertifikat')->nullable(false)->change();
            $table->string('no_register')->nullable(false)->change();

            $table->date('tanggal_ujian')->nullable(false)->change();
            $table->string('current_belt_level')->nullable(false)->change();
            $table->string('next_belt_level')->nullable(false)->change();
        });
    }
};
