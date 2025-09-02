<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Unit;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    protected bool $testMode;

    public function __construct(bool $testMode = false)
    {
        $this->testMode = $testMode;
    }

    /**
     * Sesuaikan baris header Excel.
     * Kalau header ada di baris 1 → return 1
     * Kalau header ada di baris 4 (misalnya karena ada judul di atas) → return 4
     */
    public function headingRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        // Debug: lihat isi row
        Log::info('Row dibaca dari Excel:', $row);

        if ($this->testMode) {
            return null;
        }

        // Cek minimal kolom penting
        if (empty($row['nis']) || empty($row['nama_lengkap'])) {
            Log::warning('Baris dilewati karena NIS / Nama Lengkap kosong:', $row);
            return null;
        }

        // Cari Unit
        $unit = !empty($row['unit_latihan'])
            ? Unit::whereRaw('LOWER(name) = ?', [strtolower($row['unit_latihan'])])->first()
            : null;

        // Cari Kelas
        $kelas = !empty($row['kelas'])
            ? Kelas::whereRaw('LOWER(name) = ?', [strtolower($row['kelas'])])->first()
            : null;

        $siswa = new Siswa([
            'nis'                => $row['nis'] ?? null,
            'no_register'        => $row['nomor_registrasi'] ?? null,
            'nama_lengkap'       => $row['nama_lengkap'] ?? null,
            'jenis_kelamin'      => $row['jenis_kelamin'] ?? null,
            'units_id'           => $unit?->id ?? null,
            'kelas_id'           => $kelas?->id ?? null,
            'current_belt_level' => $row['sabuk'] ?? null,
            'tempat_lahir'       => $row['tempat_lahir'] ?? null,
            'tanggal_lahir'      => !empty($row['tanggal_lahir']) ? Carbon::parse($row['tanggal_lahir']) : null,
            'golongan_darah'     => $row['golongan_darah'] ?? null,
            'image'              => $row['foto_siswa'] ?? null,
            'alamat_lengkap'     => $row['alamat_lengkap'] ?? null,
            'no_telepon'         => $row['nomor_telepon'] ?? null,
            'nama_ayah'          => $row['nama_ayah'] ?? null,
            'pekerjaan_ayah'     => $row['pekerjaan_ayah'] ?? null,
            'nama_ibu'           => $row['nama_ibu'] ?? null,
            'pekerjaan_ibu'      => $row['pekerjaan_ibu'] ?? null,
            'status'             => $row['status'] ?? 'Aktif',
            'joint_date'         => !empty($row['tanggal_bergabung']) ? Carbon::parse($row['tanggal_bergabung']) : null,
        ]);

        Log::info('Siswa siap disimpan:', $siswa->toArray());

        return $siswa;
    }
}
