<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\EventUjian;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;

class DaftarUjian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'UJIAN';
    protected static ?string $title = 'Daftar Ujian';
    protected static string $view = 'filament.siswa.pages.daftar-ujian';

    public $events = [];
    public $showVerification = false;
    public $selectedEvent;

    // Data siswa
    public $nama_lengkap;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $no_register;
    public $unit_nama;
    public $kelas_nama;
    public $current_belt_level;
    
    #[Validate('required', message: 'Sabuk berikutnya wajib dipilih.')]
    public $next_belt_level;

    public function mount(): void
    {
        $this->events = EventUjian::whereDate('tanggal_ujian', '>=', now())
            ->orderBy('tanggal_ujian', 'asc')
            ->get();
    }

    public function confirmDaftar($eventId)
    {
        $this->selectedEvent = EventUjian::findOrFail($eventId);
        $siswa = Auth::user()->siswa;

        $this->nama_lengkap = $siswa->nama_lengkap;
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir?->format('Y-m-d');
        $this->no_register = $siswa->no_register;
        $this->unit_nama = $siswa->unit?->name;
        $this->kelas_nama = $siswa->kelas?->name;
        $this->current_belt_level = $siswa->current_belt_level;
        $this->next_belt_level = '';

        $this->showVerification = true;
    }

    public function batal()
    {
        $this->reset([
            'showVerification',
            'selectedEvent',
            'next_belt_level',
        ]);
    }

    public function daftarUjian()
    {
        $this->validate(); // ✅ validasi sabuk berikutnya

        $siswa = Auth::user()->siswa;

        if (!$siswa || !$this->selectedEvent) {
            Notification::make()
                ->title('Gagal')
                ->danger()
                ->body('Data siswa atau event tidak ditemukan.')
                ->send();
            return;
        }

        // Cegah duplikasi
        if ($this->selectedEvent->siswa()->where('siswa_id', $siswa->id)->exists()) {
            Notification::make()
                ->title('Sudah Terdaftar')
                ->warning()
                ->body('Kamu sudah terdaftar pada event ini.')
                ->send();
            return;
        }

        // Simpan data pivot
        $this->selectedEvent->siswa()->attach($siswa->id, [
            'current_belt_level' => $this->current_belt_level,
            'next_belt_level'    => $this->next_belt_level,
            'keterangan'         => 'on progres',
        ]);

        $this->showVerification = false;

        Notification::make()
            ->title('Berhasil')
            ->success()
            ->body('Kamu berhasil mendaftar ujian ini.')
            ->send();
    }

    /** 🔥 Fungsi untuk batal daftar dan hapus pivot */
    public function batalDaftar($eventId)
    {
        $siswa = Auth::user()->siswa;
        $event = EventUjian::find($eventId);

        if (!$event || !$siswa) {
            Notification::make()
                ->title('Gagal')
                ->danger()
                ->body('Data siswa atau event tidak ditemukan.')
                ->send();
            return;
        }

        $event->siswa()->detach($siswa->id);

        Notification::make()
            ->title('Dibatalkan')
            ->warning()
            ->body('Pendaftaran ujian kamu telah dibatalkan.')
            ->send();

        // Refresh daftar event
        $this->mount();
    }

    /** ✅ Dropdown Sabuk Berikutnya */
    private static function beltOptions(): array
    {
        return [
            'putih'              => 'Putih',
            'kuning'             => 'Kuning',
            'kuning strip hijau' => 'Kuning Strip Hijau',
            'hijau'              => 'Hijau',
            'hijau strip biru'   => 'Hijau Strip Biru',
            'biru'               => 'Biru',
            'biru strip merah'   => 'Biru Strip Merah',
            'merah'              => 'Merah',
            'merah strip hitam 1'=> 'Merah Strip Hitam 1',
            'merah strip hitam 2'=> 'Merah Strip Hitam 2',
            'hitam'              => 'Hitam',
        ];
    }

    public function getSabukOptionsProperty()
    {
        return self::beltOptions();
    }
}
