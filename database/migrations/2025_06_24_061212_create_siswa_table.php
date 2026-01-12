<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {

            $table->id();

            /**
             * ==========================
             *  KUNCI VALIDASI REGISTRASI
             * ==========================
             */
            $table->string('no_register')->nullable()->unique();
            $table->date('tanggal_lahir')->nullable();

            /**
             * ==========================
             *  RELASI AKADEMIK
             * ==========================
             */
            $table->foreignId('units_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete();

            $table->foreignId('kelas_id')
                ->nullable()
                ->constrained('kelas')
                ->nullOnDelete();

            /**
             * ==========================
             *  RELASI USER
             * ==========================
             */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * ==========================
             *     IDENTITAS DASAR
             * ==========================
             */
            $table->string('nis')->unique();
            $table->string('nama_lengkap');

            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('golongan_darah')->nullable();

            $table->string('image')->nullable();
            $table->longText('alamat_lengkap')->nullable();
            $table->longText('no_telepon')->nullable();

            /**
             * ==========================
             *     ORANG TUA
             * ==========================
             */
            $table->longText('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();

            $table->longText('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();

            /**
             * ==========================
             *        AKADEMIK
             * ==========================
             */
            $table->string('beladiri_yang_pernah_diikuti')->nullable();
            $table->string('current_belt_level')->default('Putih');

            $table->date('joint_date')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])
          ->default('aktif')
          ->after('tanggal_lahir');

            $table->timestamps();

            /**
             * INDEX untuk validasi 3 kunci:
             * - no_register
             * - tanggal_lahir
             * - units_id
             */
            $table->index(['no_register', 'tanggal_lahir', 'units_id'], 'siswa_match_3keys_index');
        });
    }
};
