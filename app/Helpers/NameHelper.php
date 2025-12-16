<?php

namespace App\Helpers;

class NameHelper
{
    /**
     * Normalisasi nama lengkap:
     * - Hilangkan spasi berlebih
     * - Lowercase → Title Case
     * - Tangani O'Connor
     * - Tangani Mc/Mac (McDonald, MacArthur)
     */
    public static function normalize(string $name): string
    {
        // 1. Hilangkan spasi berlebih
        $name = preg_replace('/\s+/', ' ', trim($name));

        // Jika kosong → return kosong
        if ($name === '') {
            return '';
        }

        // 2. Jadikan huruf kecil semua
        $name = strtolower($name);

        // 3. Tangani nama dengan apostrophe → O'Connor, D'Arcy
        $name = preg_replace_callback('/\b\w+\'\w+\b/', function ($w) {
            return ucfirst($w[0]);
        }, $name);

        // 4. Tangani Mc dan Mac → McGregor, MacArthur
        $name = preg_replace_callback('/\b(mc|mac)(\w+)/i', function ($m) {
            return ucfirst($m[1]) . ucfirst($m[2]);
        }, $name);

        // 5. Kapital di awal setiap kata
        $name = ucwords($name);

        return $name;
    }
}
