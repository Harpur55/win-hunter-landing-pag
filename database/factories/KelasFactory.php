<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition(): array
    {
        return [
            'image'       => null,
            'name'        => 'Prestasi',
            'description' => 'Kelas default untuk testing',
            'kuota'       => '0',
            'kuota_awal'  => 2,
        ];
    }

    /**
     * State: Prestasi
     */
    public function prestasi()
    {
        return $this->state(fn () => [
            'name'       => 'Prestasi',
            'description'=> 'Kelas untuk atlit prestasi',
            'kuota_awal' => 2,
            'kuota'      => '2',
        ]);
    }

    /**
     * State: Reguler
     */
    public function reguler()
    {
        return $this->state(fn () => [
            'name'       => 'Reguler',
            'description'=> 'Kelas untuk atlit reguler',
            'kuota_awal' => 2,
            'kuota'      => '2',
        ]);
    }

    /**
     * State: Khusus
     */
    public function khusus()
    {
        return $this->state(fn () => [
            'name'       => 'Khusus',
            'description'=> 'Kelas untuk atlit khusus',
            'kuota_awal' => 99,
            'kuota'      => '99',
        ]);
    }
}
