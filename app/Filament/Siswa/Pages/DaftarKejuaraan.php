<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Siswa;
use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DaftarKejuaraan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static ?string $navigationLabel = 'Daftar Kejuaraan';
    protected static string $view = 'filament.siswa.pages.daftar-kejuaraan';

    public ?array $data = [];
    public bool $isOpen = false;
    public ?int $selectedEventId = null;
    public $events = [];
    public array $sudahTerdaftar = [];

    public function mount(): void
    {
        $this->events = Kejuaraan::orderBy('tanggal_mulai', 'asc')->get();
        $this->loadSiswaData();
        $this->loadTerdaftar();
    }

    private function loadTerdaftar(): void
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return;

        $this->sudahTerdaftar = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->pluck('kejuaraan_id')
            ->toArray();
    }

    private function loadSiswaData(): void
    {
        $siswa = Auth::user()->siswa;

        if ($siswa) {
            $this->data = [
                'nama_lengkap' => $siswa->nama_lengkap,
                'tempat_lahir' => $siswa->tempat_lahir,
                'tanggal_lahir' => $siswa->tanggal_lahir,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'sabuk' => $siswa->current_belt_level, // pastikan nama kolom sesuai
                'no_register' => $siswa->no_register ?? null, // tambahkan kolom ini
                'kategori_atlit' => $this->hitungKategoriUmur($siswa->tanggal_lahir),
                'kategori_pertandingan' => '',
                'tageuk' => '',
                'tingkat_kategori' => '',
                'berat_badan' => '',
                'tinggi_badan' => '',
            ];
        }
    }

    public function openForm(int $id): void
    {
        $this->selectedEventId = $id;
        $this->isOpen = true;
        $this->loadSiswaData();
    }

    public function batal(): void
    {
        $this->isOpen = false;
        $this->loadSiswaData();
    }

    public function daftar(): void
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa || !$this->selectedEventId) {
            Notification::make()->title('Terjadi kesalahan.')->danger()->send();
            return;
        }

        /**
         * 🔒 Validasi sabuk putih tanpa nomor registrasi
         */
        if (
            strtolower($siswa->current_belt_level ?? '') === 'putih' &&
            empty($siswa->no_register)
        ) {
            Notification::make()
                ->title('Silakan ujian terlebih dahulu ⚠️')
                ->body('Petunjuk: Nomor registrasi terdapat pada sertifikat ujian.')
                ->warning()
                ->send();
            return;
        }

        /**
         * ✅ Validasi kategori pertandingan wajib diisi
         */
        if (empty($this->data['kategori_pertandingan'])) {
            Notification::make()
                ->title('Silakan pilih kategori pertandingan terlebih dahulu.')
                ->warning()
                ->send();
            return;
        }

        /**
         * ✅ Validasi spesifik berdasarkan kategori
         */
        if ($this->data['kategori_pertandingan'] === 'kyorugi') {
            if (empty($this->data['berat_badan']) || empty($this->data['tinggi_badan'])) {
                Notification::make()
                    ->title('Berat badan dan tinggi badan wajib diisi untuk kategori Kyorugi.')
                    ->warning()
                    ->send();
                return;
            }
        }

        if ($this->data['kategori_pertandingan'] === 'poomsae') {
            if (empty($this->data['tingkat_kategori'])) {
                Notification::make()
                    ->title('Tingkat kategori wajib diisi untuk kategori Poomsae.')
                    ->warning()
                    ->send();
                return;
            }
        }

        /**
         * ✅ Cek apakah siswa sudah terdaftar
         */
        $sudah = KejuaraanSiswa::where('kejuaraan_id', $this->selectedEventId)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudah) {
            Notification::make()
                ->title('Kamu sudah terdaftar di kejuaraan ini ⚠️')
                ->warning()
                ->send();
            return;
        }

        /**
         * ✅ Simpan data ke tabel kejuaraan_siswa
         */
        KejuaraanSiswa::create([
            'kejuaraan_id' => $this->selectedEventId,
            'siswa_id' => $siswa->id,
            'nama_lengkap' => $this->data['nama_lengkap'],
            'tempat_lahir' => $this->data['tempat_lahir'],
            'tanggal_lahir' => $this->data['tanggal_lahir'],
            'jenis_kelamin' => $this->data['jenis_kelamin'],
            'sabuk' => $this->data['sabuk'],
            'kategori_pertandingan' => $this->data['kategori_pertandingan'],
            'tageuk' => $this->data['tageuk'] ?: null,
            'tingkat_kategori' => $this->data['tingkat_kategori'] ?: null,
            'kategori_atlit' => $this->data['kategori_atlit'],
            'berat_badan' => $this->data['berat_badan'] ?: null,
            'tinggi_badan' => $this->data['tinggi_badan'] ?: null,
        ]);

        $this->isOpen = false;
        $this->loadTerdaftar();

        Notification::make()
            ->title('Kamu telah terdaftar di kejuaraan ini 🎉')
            ->success()
            ->send();
    }

    private function hitungKategoriUmur(?string $tanggalLahir): string
    {
        if (!$tanggalLahir) return 'senior';
        $umur = Carbon::parse($tanggalLahir)->age;

        return match (true) {
            $umur <= 11 => 'pracadet',
            $umur <= 14 => 'cadet',
            $umur <= 17 => 'junior',
            default => 'senior',
        };
    }
}
