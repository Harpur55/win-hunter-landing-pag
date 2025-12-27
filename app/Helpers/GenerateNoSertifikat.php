<?php

use App\Models\Sertifikat;
use Illuminate\Support\Facades\DB;

if (! function_exists('generateNoSertifikat')) {

    /**
     * Generate nomor sertifikat unik per EVENT & TAHUN
     * Format: 001SCWHVI2025
     */
    function generateNoSertifikat(int $eventId): string
    {
        $prefix = 'SCWHVI';
        $year   = now()->format('Y');

        $lastNumber = Sertifikat::whereHas('eventUjianSiswa', function ($q) use ($eventId) {
                $q->where('event_ujian_id', $eventId);
            })
            ->where('no_sertifikat', 'like', "%{$prefix}{$year}")
            ->lockForUpdate() // anti duplicate saat race condition
            ->max(DB::raw("CAST(SUBSTRING(no_sertifikat, 1, 3) AS UNSIGNED)"));

        $next = ($lastNumber ?? 0) + 1;

        return str_pad($next, 3, '0', STR_PAD_LEFT) . $prefix . $year;
    }
}
