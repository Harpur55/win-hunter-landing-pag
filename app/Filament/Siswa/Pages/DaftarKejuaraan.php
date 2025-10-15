<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use Carbon\Carbon;

class DaftarKejuaraan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static string $view = 'filament.siswa.pages.daftar-kejuaraan';

    // 🧾 State
    public $events = [];
    public $isOpen = false;
    public $kejuaraanId;
    public $namaKejuaraan;

    // 🔹 Data siswa
    public $nama_lengkap;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $sabuk;
    public $kategori_atlit;

    // 🔹 Data kejuaraan
    public $kategori_pertandingan;
    public $berat_badan;
    public $tinggi_badan;
    public $tageuk;
    public $tingkat_kategori;

    public $tingkatKategoriOptions = [
        'Beginer' => 'Beginer',
        'Advance' => 'Advance',
    ];

    public function mount()
    {
        $this->events = Kejuaraan::whereDate('tanggal_mulai', '>=', Carbon::today())
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
    }

    // 🔹 Buka form daftar
    public function openForm($id)
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->kejuaraanId = $id;

        $kejuaraan = Kejuaraan::find($id);
        $this->namaKejuaraan = $kejuaraan ? $kejuaraan->nama_kejuaraan : '-';

        $siswa = Auth::user()->siswa;
        if ($siswa) {
            $this->nama_lengkap = $siswa->nama_lengkap;
            $this->tempat_lahir = $siswa->tempat_lahir;
            $this->tanggal_lahir = $siswa->tanggal_lahir;
            $this->jenis_kelamin = $siswa->jenis_kelamin;
            $this->sabuk = $siswa->current_belt_level ?? '-';
            $this->kategori_atlit = $this->hitungKategoriAtlit($this->tanggal_lahir);
        }
    }

    // 🔹 Hitung kategori atlit
    private function hitungKategoriAtlit($tanggal)
    {
        if (!$tanggal) return null;
        $umur = Carbon::parse($tanggal)->age;

        return match (true) {
            $umur <= 11 => 'Pra Cadet',
            $umur <= 14 => 'Cadet',
            $umur <= 17 => 'Junior',
            default => 'Senior',
        };
    }

    public function updatedTanggalLahir()
    {
        $this->kategori_atlit = $this->hitungKategoriAtlit($this->tanggal_lahir);
    }

    // 🔹 Simpan data pendaftaran
    public function daftar()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'sabuk' => 'required|string|max:100',
            'kategori_pertandingan' => 'required|in:kyorugi,poomsae',
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'tageuk' => 'nullable|string|max:255',
            'tingkat_kategori' => 'nullable|in:Beginer,Advance',
            'kategori_atlit' => 'required|string|max:100',
        ]);

        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            Notification::make()
                ->title('Data siswa tidak ditemukan!')
                ->body('Silakan lengkapi profil terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        // Cegah double daftar
        $sudahDaftar = KejuaraanSiswa::where('kejuaraan_id', $this->kejuaraanId)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahDaftar) {
            Notification::make()
                ->title('Kamu sudah terdaftar ⚠️')
                ->body('Kamu sudah mendaftar pada kejuaraan ini sebelumnya.')
                ->warning()
                ->send();
            return;
        }

        // Simpan ke DB
        KejuaraanSiswa::create([
            'kejuaraan_id' => $this->kejuaraanId,
            'siswa_id' => $siswa->id,
            'nama_lengkap' => $this->nama_lengkap,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'sabuk' => $this->sabuk,
            'kategori_pertandingan' => $this->kategori_pertandingan,
            'tageuk' => $this->kategori_pertandingan === 'poomsae' ? $this->tageuk : null,
            'tingkat_kategori' => $this->kategori_pertandingan === 'poomsae' ? $this->tingkat_kategori : null,
            'kategori_atlit' => $this->kategori_atlit,
            'berat_badan' => $this->kategori_pertandingan === 'kyorugi' ? $this->berat_badan : null,
            'tinggi_badan' => $this->kategori_pertandingan === 'kyorugi' ? $this->tinggi_badan : null,
            'medali' => null,
        ]);

        // 🎉 Notifikasi sukses
        Notification::make()
            ->title('Selamat 🎉')
            ->body("Kamu telah berhasil terdaftar di kejuaraan *{$this->namaKejuaraan}*!")
            ->success()
            ->send();

        $this->resetForm();
        $this->isOpen = false;
    }

    // 🔹 Tutup modal
    public function batal()
    {
        $this->resetForm();
        $this->isOpen = false;
    }

    private function resetForm()
    {
        $this->reset([
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'sabuk',
            'kategori_pertandingan',
            'berat_badan',
            'tinggi_badan',
            'tageuk',
            'tingkat_kategori',
            'kategori_atlit',
            'namaKejuaraan',
        ]);
    }
}
