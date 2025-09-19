<?php

namespace App\Helpers;

class ExcelHelper
{
    public static function normalize(?string $value): ?string
    {
        if (!$value) return null;

        $value = trim($value);

        // hapus tanda baca (.,,)
        $value = preg_replace('/[^\p{L}\p{N}\s]/u', '', $value);

        // ubah jadi lowercase
        $value = strtolower($value);

        // rapikan spasi berlebih
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    }
}
