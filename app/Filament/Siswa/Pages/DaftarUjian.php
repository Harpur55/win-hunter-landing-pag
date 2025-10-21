<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\EventUjian;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class DaftarUjian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'UJIAN';
    protected static ?string $title = 'Daftar Ujian Taekwondo Win Hunter';
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

    // Cek event baru (dibuat dalam 3 hari terakhir)
    $latestEvent = EventUjian::where('created_at', '>=', now()->subDays(3))
        ->latest('created_at')
        ->first();

    if ($latestEvent && !session()->has('ujian_notification_seen')) {
        // Tampilkan popup
        Notification::make()
            ->title('📢 Event Ujian Baru!')
            ->body('Ujian "' . $latestEvent->nama_ujian . '" telah dibuka. Yuk, daftar sekarang!')
            ->success()
            ->icon('heroicon-o-megaphone')
            ->send();

        // Tambahkan angka badge di sidebar
        session(['ujian_notification_count' => 1]);
    } else {
        session(['ujian_notification_count' => 0]);
    }

    // Tandai sudah dilihat ketika halaman dibuka
    session(['ujian_notification_seen' => true]);
}




    public function confirmDaftar($eventId)
    {
        $this->selectedEvent = EventUjian::findOrFail($eventId);

        // 🔒 Cek apakah pendaftaran ditutup
        if ($this->selectedEvent->is_registration_closed) {
            Notification::make()
                ->title('Pendaftaran Ditutup ⛔')
                ->body('Pendaftaran untuk kejuaraan ini telah ditutup oleh panitia.')
                ->danger()
                ->send();
            return;
        }

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
        $this->validate();

        $siswa = Auth::user()->siswa;
        $event = $this->selectedEvent;

        if (!$siswa || !$event) {
            Notification::make()
                ->title('Gagal')
                ->danger()
                ->body('Data siswa atau event tidak ditemukan.')
                ->send();
            return;
        }

        // 🚫 Cek status pendaftaran
        if ($event->is_registration_closed) {
            Notification::make()
                ->title('Pendaftaran Ditutup ⛔')
                ->body('Pendaftaran untuk kejuaraan ini telah ditutup oleh panitia.')
                ->danger()
                ->send();
            return;
        }

        // 🕓 Cek tanggal selesai jika ada
        if (isset($event->tanggal_selesai) && Carbon::now()->greaterThan(Carbon::parse($event->tanggal_selesai))) {
            Notification::make()
                ->title('Pendaftaran sudah ditutup ⛔')
                ->body('Batas waktu pendaftaran telah berakhir pada ' . Carbon::parse($event->tanggal_selesai)->format('d M Y') . '.')
                ->danger()
                ->send();
            return;
        }

        // 🔁 Cegah duplikasi
        if ($event->siswa()->where('siswa_id', $siswa->id)->exists()) {
            Notification::make()
                ->title('Sudah Terdaftar')
                ->warning()
                ->body('Kamu sudah terdaftar pada event ini.')
                ->send();
            return;
        }

        // ✅ Simpan data pendaftaran
        $event->siswa()->attach($siswa->id, [
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

        $this->mount();
    }

    private static function beltOptions(): array
    {
        return [
            'putih' => 'Putih',
            'kuning' => 'Kuning',
            'kuning strip hijau' => 'Kuning Strip Hijau',
            'hijau' => 'Hijau',
            'hijau strip biru' => 'Hijau Strip Biru',
            'biru' => 'Biru',
            'biru strip merah' => 'Biru Strip Merah',
            'merah' => 'Merah',
            'merah strip hitam 1' => 'Merah Strip Hitam 1',
            'merah strip hitam 2' => 'Merah Strip Hitam 2',
            'hitam' => 'Hitam',
        ];
    }

    public function getSabukOptionsProperty()
    {
        return self::beltOptions();
    }
    public static function getNavigationBadge(): ?string
{
    // Ambil dari session
    return session('ujian_notification_count', 0) > 0
        ? (string) session('ujian_notification_count')
        : null;
}
}
