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
        Schema::create('weight_class', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_atlit'); // pracadet, cadet, junior, senior
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->decimal('min_kg', 5, 2)->nullable(); // null = tanpa batas bawah
            $table->decimal('max_kg', 5, 2)->nullable(); // null = +kg
            $table->string('label'); // -58 kg, +80 kg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_class');
    }
};
