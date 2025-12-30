<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\EventUjian;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;

class DaftarUjianForm extends Page
{
    protected static ?string $title = 'Pendaftaran Ujian';
    protected static string $view = 'filament.pages.daftar-ujian-form';

    public $event;

    // input form (event_ujian_siswa)
    #[Validate('required')]
    public $nama_lengkap;

    #[Validate('required')]
    public $tempat_lahir;

    #[Validate('required|date')]
    public $tanggal_lahir;

    #[Validate('required')]
    public $no_register;

    #[Validate('required')]
    public $current_belt_level;

    #[Validate('required')]
    public $next_belt_level;

    public function mount($eventId)
    {
        $this->event = EventUjian::findOrFail($eventId);
    }

    public function submit()
    {
        $this->validate();

        DB::table('event_ujian_siswa')->insert([
            'event_ujian_id'    => $this->event->id,
            'nama_lengkap'      => $this->nama_lengkap,
            'tempat_lahir'      => $this->tempat_lahir,
            'tanggal_lahir'     => $this->tanggal_lahir,
            'no_register'       => $this->no_register,
            'current_belt_level'=> $this->current_belt_level,
            'next_belt_level'   => $this->next_belt_level,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        Notification::make()
            ->title('Pendaftaran Berhasil!')
            ->success()
            ->body('Data pendaftaran ujian kamu sudah kami simpan.')
            ->send();

        return redirect()->route('daftar-ujian-list');
    }
}
