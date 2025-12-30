<?php

namespace App\Helpers;

class NameHelper
{
    /**
     * Normalisasi untuk VALIDASI DATABASE
     * (aman untuk pencocokan)
     */
    public static function normalizeForCompare(string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim($name));
        return strtolower($name);
    }

    /**
     * Normalisasi untuk TAMPILAN
     */
    public static function normalizeForDisplay(string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim($name));

        if ($name === '') {
            return '';
        }

        $name = strtolower($name);

        // Apostrophe → O'Connor, D'Arcy
        $name = preg_replace_callback("/\b([a-z]+)'([a-z]+)\b/", function ($m) {
            return ucfirst($m[1]) . "'" . ucfirst($m[2]);
        }, $name);

        // Mc / Mac → McDonald, MacArthur
        $name = preg_replace_callback('/\b(mc|mac)([a-z]+)/i', function ($m) {
            return ucfirst(strtolower($m[1])) . ucfirst($m[2]);
        }, $name);

        // Kapital awal setiap kata
        return ucwords($name);
    }

    public static function normalize(string $name): string
{
    // default ke tampilan
    return self::normalizeForDisplay($name);
}

}
