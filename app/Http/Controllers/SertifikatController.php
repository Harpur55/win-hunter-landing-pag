<?php

namespace App\Http\Controllers;

use App\Models\UjianSiswa;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function generate(int $eventId, int $siswaId): Sertifikat
    {
        return DB::transaction(function () use ($eventId, $siswaId) {

            $pivot = UjianSiswa::with(['siswa', 'eventUjian'])
                ->where('event_ujian_id', $eventId)
                ->where('siswa_id', $siswaId)
                ->lockForUpdate()
                ->firstOrFail();

            // 🔒 Proteksi
            if ($pivot->sertifikat) {
                throw new \Exception('Sertifikat sudah digenerate.');
            }

            if ($pivot->keterangan !== 'lulus') {
                throw new \Exception('Siswa belum lulus.');
            }

            // 🔢 No Sertifikat
            $noSertifikat = generateNoSertifikat($eventId);

            /**
             * 🧾 Generate PDF
             * ⬇️ PENTING: nama variabel HARUS SAMA dengan view
             */
            $pdf = Pdf::loadView('pdf.sertifikat-ujian', [
                'pivot'        => $pivot,
                'siswa'        => $pivot->siswa,
                'event'        => $pivot->eventUjian,
                'noSertifikat' => $noSertifikat,
            ])->setPaper('a4', 'portrait');

            $fileName = 'sertifikat/'
                . now()->format('YmdHis')
                . "_event{$eventId}_siswa{$siswaId}.pdf";

            Storage::disk('public')->put($fileName, $pdf->output());

            // 💾 Simpan DB
            $sertifikat = $pivot->sertifikat()->create([
                'event_ujian_siswa_id' => $pivot->id,
                'siswa_id'             => $siswaId,
                'no_sertifikat'        => $noSertifikat,
                'no_register'          => $pivot->no_register,
                'nama_lengkap'         => $pivot->nama_lengkap,
                'tanggal_lahir'        => $pivot->tanggal_lahir,
                'tanggal_ujian'        => $pivot->eventUjian->tanggal_ujian,
                'current_belt_level'   => $pivot->current_belt_level,
                'next_belt_level'      => $pivot->next_belt_level,
                'file_pdf'             => $fileName,
                'lokasi_ujian'        => $pivot->eventUjian->lokasi_ujian,  
                'generated_at'         => now(),
                'is_active'            => true,
            ]);

            // Opsional
            $pivot->update([
                'certificate_path' => $fileName,
            ]);

            return $sertifikat;
        });
    }
}
