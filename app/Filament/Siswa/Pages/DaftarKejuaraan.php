<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DaftarKejuaraan extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static ?string $navigationLabel = 'Daftar Kejuaraan';
    protected static string  $view            = 'filament.siswa.pages.daftar-kejuaraan';
    protected static ?string $slug            = 'daftar-kejuaraan';

    public Collection $events;
    public array      $data           = [];
    public bool       $isOpen         = false;
    public ?int       $selectedEventId = null;

    public array $sudahTerdaftar  = [];
    public array $sudahDapatMedali = [];

    public int  $kuotaMaks     = 0;
    public int  $kuotaTerpakai = 0;
    public bool $kuotaHabis    = false;

    /**
     * 1 = gunakan kuota kelas
     * 0 = tidak gunakan kuota kelas
     */
    public int $pakaiKuota = 1;

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */
    public function mount(): void
    {
        $this->loadEvents();
        $this->loadSiswaData();
        $this->loadTerdaftar();
        $this->loadMedali();
        $this->hitungKuota();

        // Hilangkan badge navigation
        session(['kejuaraan_seen' => true]);

        $this->checkNotifications();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper properti
    |--------------------------------------------------------------------------
    */
    protected function siswa(): ?Siswa
    {
        return Auth::user()?->siswa;
    }

    protected function isPakaiKuota(): bool
    {
        return (bool) $this->pakaiKuota;
    }

    /*
    |--------------------------------------------------------------------------
    | Load data
    |--------------------------------------------------------------------------
    */
    private function loadEvents(): void
    {
        $tahunSekarang = Carbon::now()->year;
        $tahunDepan    = $tahunSekarang + 1;

        $this->events = Kejuaraan::query()
            ->whereYear('tanggal_mulai', '>=', $tahunSekarang)
            ->whereYear('tanggal_mulai', '<=', $tahunDepan)
            ->orderBy('tanggal_mulai')
            ->get();
    }

    private function loadSiswaData(): void
    {
        $siswa = $this->siswa();
        if (! $siswa) {
            $this->data = [];
            return;
        }

        $this->data = [
            'nama_lengkap'        => $siswa->nama_lengkap,
            'tempat_lahir'        => $siswa->tempat_lahir,
            'tanggal_lahir'       => $siswa->tanggal_lahir
                ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')
                : null,
            'jenis_kelamin'       => $siswa->jenis_kelamin,
            'sabuk'               => $siswa->current_belt_level,
            'no_register'         => $siswa->no_register,
            'kategori_atlit'      => $this->hitungKategoriUmur($siswa->tanggal_lahir),
            'kategori_pertandingan' => '',
            'tageuk'              => '',
            'tingkat_kategori'    => '',
            'berat_badan'         => '',
            'tinggi_badan'        => '',
        ];

        // setiap buka form, default pakai kuota
        $this->pakaiKuota = 1;
    }

    private function loadTerdaftar(): void
    {
        $siswa = $this->siswa();
        if (! $siswa) {
            $this->sudahTerdaftar = [];
            return;
        }

        $this->sudahTerdaftar = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->pluck('kejuaraan_id')
            ->toArray();
    }

    private function loadMedali(): void
    {
        $siswa = $this->siswa();
        if (! $siswa) {
            $this->sudahDapatMedali = [];
            return;
        }

        $this->sudahDapatMedali = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->whereNotNull('medali')
            ->pluck('kejuaraan_id')
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Kuota
    |--------------------------------------------------------------------------
    */
    private function hitungKuota(): void
    {
        $siswa = $this->siswa();
        if (! $siswa) {
            $this->kuotaMaks     = 0;
            $this->kuotaTerpakai = 0;
            $this->kuotaHabis    = false;
            return;
        }

        $totalPakaiKuota = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->where('use_kuota', true)
            ->count();

        $kuotaAwal    = $siswa->kelas?->kuota_awal ?? 0;
        $sisaKuotaReal = max(0, $kuotaAwal - $totalPakaiKuota);

        if (($siswa->sisa_kuota ?? 0) !== $sisaKuotaReal) {
            $siswa->update(['sisa_kuota' => $sisaKuotaReal]);
        }

        $this->kuotaMaks     = $kuotaAwal;
        $this->kuotaTerpakai = $totalPakaiKuota;
        $this->kuotaHabis    = $sisaKuotaReal <= 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Notifikasi umum
    |--------------------------------------------------------------------------
    */
    private function checkNotifications(): void
    {
        if ($this->events->isEmpty()) {
            Notification::make()
                ->title('Belum Ada Kejuaraan 📭')
                ->body('Belum ada kejuaraan untuk tahun ini atau tahun depan.')
                ->warning()
                ->send();

            return;
        }

        if ($this->events->every(fn (Kejuaraan $event) => $event->is_registration_closed)) {
            Notification::make()
                ->title('Pendaftaran Ditutup ⛔')
                ->body('Semua kejuaraan saat ini sedang ditutup oleh panitia.')
                ->warning()
                ->send();
        }

        if ($this->kuotaHabis) {
            Notification::make()
                ->title('Kuota Kamu Sudah Habis 🎯')
                ->body('Kamu telah menggunakan seluruh kuota kejuaraan yang tersedia untuk kelasmu.')
                ->warning()
                ->send();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Form pendaftaran
    |--------------------------------------------------------------------------
    */
    public function openForm(int $id): void
    {
        if ($this->isPakaiKuota() && $this->kuotaHabis) {
            Notification::make()
                ->title('Kuota Kamu Sudah Habis 🎯')
                ->body('Kamu tidak bisa mendaftar kejuaraan baru menggunakan kuota kelas. Pilih "Tidak gunakan kuota" jika ingin tetap daftar.')
                ->warning()
                ->send();

            return;
        }

        $event = Kejuaraan::find($id);
        if (! $event) {
            Notification::make()->title('Kejuaraan tidak ditemukan.')->danger()->send();
            return;
        }

        if ($event->is_registration_closed) {
            Notification::make()
                ->title('Pendaftaran Ditutup ⛔')
                ->body('Pendaftaran untuk kejuaraan ini telah ditutup oleh panitia.')
                ->danger()
                ->send();

            return;
        }

        $this->selectedEventId = $id;
        $this->isOpen          = true;
        $this->loadSiswaData();
    }

    public function batal(): void
    {
        $this->isOpen = false;
        $this->loadSiswaData();
    }

    public function daftar(): void
    {
        $siswa = $this->siswa();
        if (! $siswa || ! $this->selectedEventId) {
            Notification::make()->title('Terjadi kesalahan.')->danger()->send();
            return;
        }

        if (empty($this->data['kategori_pertandingan'])) {
            Notification::make()
                ->title('⚠️ Kategori pertandingan harus dipilih!')
                ->warning()
                ->send();

            return;
        }

        if ($this->isPakaiKuota() && $this->kuotaHabis) {
            Notification::make()
                ->title('Kuota Kamu Sudah Habis 🎯')
                ->body('Pilih "Tidak gunakan kuota" untuk tetap bisa daftar.')
                ->warning()
                ->send();

            return;
        }

        $event = Kejuaraan::find($this->selectedEventId);
        if (! $event) {
            Notification::make()->title('Kejuaraan tidak ditemukan.')->danger()->send();
            return;
        }

        if ($event->is_registration_closed) {
            Notification::make()
                ->title('Pendaftaran Ditutup ⛔')
                ->body('Pendaftaran untuk kejuaraan ini telah ditutup oleh panitia.')
                ->danger()
                ->send();

            return;
        }

        $sudah = KejuaraanSiswa::where('kejuaraan_id', $this->selectedEventId)
            ->where('siswa_id', $siswa->id)
            ->where('kategori_pertandingan', $this->data['kategori_pertandingan'])
            ->exists();

        if ($sudah) {
            Notification::make()
                ->title('Kamu sudah terdaftar di kategori ini ⚠')
                ->body('Kamu sudah mendaftar kategori yang sama di kejuaraan ini.')
                ->warning()
                ->send();

            return;
        }

        KejuaraanSiswa::create([
            'kejuaraan_id'         => $this->selectedEventId,
            'siswa_id'             => $siswa->id,
            'nama_lengkap'         => $this->data['nama_lengkap'],
            'tempat_lahir'         => $this->data['tempat_lahir'],
            'tanggal_lahir'        => $this->data['tanggal_lahir'],
            'jenis_kelamin'        => $this->data['jenis_kelamin'],
            'sabuk'                => $this->data['sabuk'],
            'kategori_pertandingan'=> $this->data['kategori_pertandingan'],
            'tageuk'               => $this->data['tageuk'] ?: null,
            'tingkat_kategori'     => $this->data['tingkat_kategori'] ?: null,
            'kategori_atlit'       => $this->data['kategori_atlit'],
            'berat_badan'          => $this->data['berat_badan'] ?: null,
            'tinggi_badan'         => $this->data['tinggi_badan'] ?: null,
            'use_kuota'            => $this->isPakaiKuota(),
        ]);

        $this->isOpen = false;
        $this->loadTerdaftar();
        $this->hitungKuota();

        $statusKuota = $this->isPakaiKuota()
            ? '✅ Menggunakan kuota kelas'
            : '➡️ Tanpa kuota kelas';

        Notification::make()
            ->title('Kamu telah terdaftar di kejuaraan ini 🎉')
            ->body('Kategori: ' . strtoupper($this->data['kategori_pertandingan']) . ". {$statusKuota}")
            ->success()
            ->send();
    }

    /*
    |--------------------------------------------------------------------------
    | Utilitas lain
    |--------------------------------------------------------------------------
    */
    private function hitungKategoriUmur(?string $tanggalLahir): string
    {
        if (! $tanggalLahir) {
            return 'senior';
        }

        $umur = Carbon::parse($tanggalLahir)->age;

        return match (true) {
            $umur <= 11 => 'pracadet',
            $umur <= 14 => 'cadet',
            $umur <= 17 => 'junior',
            default      => 'senior',
        };
    }

    public function batalDaftar(int $eventId): void
    {
        $siswa = $this->siswa();
        if (! $siswa) {
            return;
        }

        $record = KejuaraanSiswa::where('kejuaraan_id', $eventId)
            ->where('siswa_id', $siswa->id)
            ->first();

        if (! $record) {
            return;
        }

        $record->delete();

        $this->loadTerdaftar();
        $this->hitungKuota();

        Notification::make()
            ->title('Pendaftaran dibatalkan')
            ->body('Kuota kelas akan otomatis disesuaikan.')
            ->success()
            ->send();
    }

    public function getMedaliByEventId(int $eventId): ?string
    {
        $siswa = $this->siswa();
        if (! $siswa) {
            return null;
        }

        return KejuaraanSiswa::where('kejuaraan_id', $eventId)
            ->where('siswa_id', $siswa->id)
            ->value('medali');
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation badge
    |--------------------------------------------------------------------------
    */
    public static function getNavigationBadge(): ?string
    {
        $user  = Auth::user();
        $siswa = $user?->siswa;

        if (! $siswa || session('kejuaraan_seen')) {
            return null;
        }

        $jumlahBaru = Kejuaraan::whereNotIn('id', function ($query) use ($siswa) {
            $query->select('kejuaraan_id')
                ->from('kejuaraan_siswa')
                ->where('siswa_id', $siswa->id);
        })->count();

        return $jumlahBaru > 0 ? (string) $jumlahBaru : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
