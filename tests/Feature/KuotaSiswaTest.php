<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KejuaraanSiswa;

class KuotaSiswaTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function tanpa_kuota_tidak_mengurangi_kuota()
    {
        $kelas = Kelas::factory()->create([
            'kuota_awal' => 3,
        ]);

        $siswa = Siswa::factory()->create([
            'kelas_id'   => $kelas->id,
            'sisa_kuota' => 3,
        ]);

        // Daftar TANPA kuota
        KejuaraanSiswa::factory()->create([
            'siswa_id'  => $siswa->id,
            'use_kuota' => false,
        ]);

        // Hitung ulang kuota
        $terpakai = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->where('use_kuota', true)
            ->count();

        $sisa = $kelas->kuota_awal - $terpakai;

        $this->assertEquals(3, $sisa);
    }
    /** @test */
public function pakai_kuota_mengurangi_kuota()
{
    $kelas = Kelas::factory()->create([
        'kuota_awal' => 3,
    ]);

    $siswa = Siswa::factory()->create([
        'kelas_id'   => $kelas->id,
        'sisa_kuota' => 3,
    ]);

    // Daftar PAKAI kuota
    KejuaraanSiswa::factory()->create([
        'siswa_id'  => $siswa->id,
        'use_kuota' => true,
    ]);

    $terpakai = KejuaraanSiswa::where('siswa_id', $siswa->id)
        ->where('use_kuota', true)
        ->count();

    $sisa = $kelas->kuota_awal - $terpakai;

    $this->assertEquals(2, $sisa);
}

}

