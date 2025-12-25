<?php

namespace App\Services;

use App\Models\WeightClass;

class WeightClassService
{
    /**
     * Resolve kelas berat berdasarkan BB
     */
    public static function resolve(
        string $kategoriAtlit,
        string $jenisKelamin,
        float $beratBadan
    ): ?WeightClass {

        return WeightClass::query()
            ->where('kategori_atlit', $kategoriAtlit)
            ->where('jenis_kelamin', $jenisKelamin)
            ->where(function ($q) use ($beratBadan) {
                $q->where('min_kg', '<=', $beratBadan)
                  ->where('max_kg', '>=', $beratBadan);
            })
            ->first();
    }
}
