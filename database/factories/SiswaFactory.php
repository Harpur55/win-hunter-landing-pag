<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        // ✅ Pastikan SELALU ada unit (aman walau DB kosong)
        $unitId = Unit::query()->inRandomOrder()->value('id');

        if (! $unitId) {
            $unitId = Unit::factory()->create()->id;
        }

        return [
            'nama_lengkap'   => $this->faker->name,
            'tempat_lahir'   => 'Jakarta',
            'alamat_lengkap' => $this->faker->address,
            'no_telepon'     => $this->faker->phoneNumber,

            // 🔗 Relasi
            'kelas_id' => Kelas::factory(),
            'units_id' => $unitId,

            'jenis_kelamin'  => 'L',
            'tanggal_lahir'  => '2010-01-01',
        ];
    }
}
