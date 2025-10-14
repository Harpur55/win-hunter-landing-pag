<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use Carbon\Carbon;

class DaftarKejuaraan extends Page
{
    protected static ?string $title = 'Daftar Kejuaraan';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static string $view = 'filament.siswa.pages.daftar-kejuaraan';

    public bool $isOpen = false;
    public ?int $kejuaraanId = null;

    public $kejuaraans;

    // 🧍 Field data siswa
    public $nama_lengkap;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $kategori_atlit;

    // 🥋 Field kategori pertandingan
    public $kategori_pertandingan;
    public $berat_badan;
    public $tinggi_badan;
    public $tageuk;
    public $tingkat_kategori;

    public function mount(): void
    {
        $this->kejuaraans = Kejuaraan::all();

        $siswa = Auth::user()->siswa;

        if ($siswa) {
            $this->nama_lengkap   = $siswa->nama_lengkap;
            $this->jenis_kelamin  = $siswa->jenis_kelamin;
            $this->tempat_lahir   = $siswa->tempat_lahir;
            $this->tanggal_lahir  = $siswa->tanggal_lahir;
            $this->kategori_atlit = $this->hitungKategoriUmur($siswa->tanggal_lahir);
        }
    }

    public function bukaFormDaftar(int $kejuaraanId): void
    {
        $this->resetForm();
        $this->kejuaraanId = $kejuaraanId;
        $this->isOpen = true;
        $this->dispatch('openModalDaftar');
    }

    public function tutupForm(): void
    {
        $this->isOpen = false;
    }

    public function updatedTanggalLahir($value)
    {
        $this->kategori_atlit = $this->hitungKategoriUmur($value);
    }

    protected function hitungKategoriUmur($tanggalLahir): ?string
    {
        if (!$tanggalLahir) return null;
        $umur = Carbon::parse($tanggalLahir)->age;

        return match (true) {
            $umur < 10 => 'pracadet',
            $umur < 14 => 'cadet',
            $umur < 18 => 'junior',
            default => 'senior',
        };
    }

    public function daftar(): void
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            Notification::make()->title('Data siswa tidak ditemukan.')->danger()->send();
            return;
        }

        if (!$this->kejuaraanId) {
            Notification::make()->title('Kejuaraan tidak valid.')->danger()->send();
            return;
        }

        KejuaraanSiswa::create([
            'kejuaraan_id' => $this->kejuaraanId,
            'siswa_id' => $siswa->id,
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'kategori_pertandingan' => $this->kategori_pertandingan,
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'tageuk' => $this->tageuk,
            'tingkat_kategori' => $this->tingkat_kategori,
            'kategori_atlit' => $this->kategori_atlit,
        ]);

        Notification::make()
            ->title('Pendaftaran berhasil dikirim!')
            ->success()
            ->send();

        $this->resetForm();
        $this->isOpen = false;
    }

    private function resetForm(): void
    {
        $this->reset([
            'kategori_pertandingan',
            'berat_badan',
            'tinggi_badan',
            'tageuk',
            'tingkat_kategori',
        ]);
    }
}
