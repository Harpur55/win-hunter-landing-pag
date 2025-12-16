<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Membuat record siswa default ketika user baru register
     * Tujuannya agar form profil tidak error.
     */
    public function getOrCreateSiswaForUser($user): Siswa
    {
        $defaultUnitId = 1; // ← isi dengan ID unit "Waterland Metland Transyogi"

        return Siswa::firstOrCreate(
            ['user_id' => $user->id],
            [
                // Identitas minimum
                'nis'            => 'AUTO-' . rand(100000, 999999),
                'nama_lengkap'   => $user->name ?? 'Nama Belum Diisi',

                // Default biodata — supaya tidak error NOT NULL
                'jenis_kelamin'  => 'Laki-laki',
                'tempat_lahir'   => 'Bogor',
                'tanggal_lahir'  => now()->toDateString(),

                // Default unit wajib (tidak boleh null)
                'units_id'       => $defaultUnitId,

                // Boleh null (tidak mengganggu)
                'kelas_id'       => null,

                'current_belt_level' => 'Putih',
                'status'             => 'Aktif',
            ]
        );
    }

    /**
     * Mencocokkan data akademik berdasarkan 3 kunci:
     * - no_register
     * - tanggal_lahir
     * - units_id
     */
    public function matchAkademik(array $data): ?Siswa
    {
        if (
            empty($data['no_register']) ||
            empty($data['tanggal_lahir']) ||
            empty($data['units_id'])
        ) {
            return null;
        }

        return Siswa::where('no_register', $data['no_register'])
            ->where('tanggal_lahir', $data['tanggal_lahir'])
            ->where('units_id', $data['units_id'])
            ->first();
    }

    /**
     * Sinkron profil user dengan data akademik siswa.
     * Data pedigree ditarik dari data sekolah (matched).
     */
    public function updateSiswa($user, array $data, Siswa $matched): Siswa
    {
        return DB::transaction(function () use ($user, $data, $matched) {

            // Tidak boleh diganti user
            $data['kelas_id'] = $matched->kelas_id;
            $data['units_id'] = $matched->units_id;
            $data['current_belt_level'] = $matched->current_belt_level;

            // Email selalu dari tabel users
            $data['email'] = $user->email;

            // Update atau buat
            $siswa = Siswa::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            // Update user.name agar sama dengan nama resmi siswa
            $user->update([
                'name' => $siswa->nama_lengkap,
            ]);

            return $siswa;
        });
    }
}
