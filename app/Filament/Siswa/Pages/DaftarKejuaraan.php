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
use App\Helpers\DaftarKejuaraanHelper;

class DaftarKejuaraan extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static ?string $navigationLabel = 'Daftar Kejuaraan';
    protected static string  $view            = 'filament.siswa.pages.daftar-kejuaraan';
    protected static ?string $slug            = 'daftar-kejuaraan';

    public Collection $events;
    public array $data = [];
    public bool $isOpen = false;
    public ?int $selectedEventId = null;

    public array $sudahTerdaftar = [];
    public array $sudahDapatMedali = [];

    public int  $kuotaMaks = 0;
    public int  $kuotaTerpakai = 0;
    public bool $kuotaHabis = false;

    /**
     * 1 = gunakan kuota
     * 0 = tidak gunakan kuota
     */
    public int $pakaiKuota = 1;

    /* -------------------------------------------------------------------------- */
    /* Lifecycle                                                                  */
    /* -------------------------------------------------------------------------- */

    public function mount(): void
    {
        $this->loadEvents();
        $this->loadSiswaData();
        $this->loadTerdaftar();
        $this->loadMedali();
        $this->hitungKuota();

        session(['kejuaraan_seen' => true]);

        $this->checkNotifications();
    }

    /* -------------------------------------------------------------------------- */
    /* Helper                                                                     */
    /* -------------------------------------------------------------------------- */

    protected function siswa(): ?Siswa
    {
        return Auth::user()?->siswa;
    }

    protected function isPakaiKuota(): bool
    {
        return (bool) $this->pakaiKuota;
    }

    /* -------------------------------------------------------------------------- */
    /* Load data                                                                  */
    /* -------------------------------------------------------------------------- */

    private function loadEvents(): void
    {
        $tahun = Carbon::now()->year;

        $this->events = Kejuaraan::whereYear('tanggal_mulai', '>=', $tahun)
            ->whereYear('tanggal_mulai', '<=', $tahun + 1)
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
            'nama_lengkap'           => $siswa->nama_lengkap,
            'tempat_lahir'           => $siswa->tempat_lahir,
            'tanggal_lahir'          => $siswa->tanggal_lahir
                ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')
                : null,
            'jenis_kelamin'          => $siswa->jenis_kelamin,
            'sabuk'                  => $siswa->current_belt_level,
            'no_register'            => $siswa->no_register,
            'kategori_atlit'         => $this->hitungKategoriUmur($siswa->tanggal_lahir),
            'kategori_pertandingan'  => '',
            'tageuk'                 => '',
            'tingkat_kategori'       => '',
            'berat_badan'            => '',
            'tinggi_badan'           => '',
        ];

        /**
         * 🔒 Default kuota DIKUNCI oleh helper
         * Reguler → selalu false
         */
        $this->pakaiKuota = DaftarKejuaraanHelper::pakaiKuota($siswa, true) ? 1 : 0;
    }

    private function loadTerdaftar(): void
    {
        $siswa = $this->siswa();
        $this->sudahTerdaftar = $siswa
            ? KejuaraanSiswa::where('siswa_id', $siswa->id)->pluck('kejuaraan_id')->toArray()
            : [];
    }

    private function loadMedali(): void
    {
        $siswa = $this->siswa();
        $this->sudahDapatMedali = $siswa
            ? KejuaraanSiswa::where('siswa_id', $siswa->id)
                ->whereNotNull('medali')
                ->pluck('kejuaraan_id')
                ->toArray()
            : [];
    }

    /* -------------------------------------------------------------------------- */
    /* Kuota                                                                      */
    /* -------------------------------------------------------------------------- */

    private function hitungKuota(): void
    {
        $siswa = $this->siswa();
        if (! $siswa) return;

        $terpakai = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->where('use_kuota', true)
            ->count();

        $kuotaAwal = $siswa->kelas?->kuota_awal ?? 0;
        $sisa = max(0, $kuotaAwal - $terpakai);

        if ($siswa->sisa_kuota !== $sisa) {
            $siswa->update(['sisa_kuota' => $sisa]);
        }

        $this->kuotaMaks = $kuotaAwal;
        $this->kuotaTerpakai = $terpakai;
        $this->kuotaHabis = $sisa <= 0;
    }

    /* -------------------------------------------------------------------------- */
    /* Form                                                                      */
    /* -------------------------------------------------------------------------- */

    public function openForm(int $id): void
    {
        $siswa = $this->siswa();

        // 🔒 VALIDASI REGULER (maks 2 / tahun)
        if ($msg = DaftarKejuaraanHelper::pesanErrorReguler($siswa)) {
            Notification::make()
                ->title('Batas Kejuaraan ⚠️')
                ->body($msg)
                ->danger()
                ->send();
            return;
        }

        $event = Kejuaraan::find($id);
        if (! $event || $event->is_registration_closed) {
            Notification::make()->title('Pendaftaran ditutup')->danger()->send();
            return;
        }

        $this->selectedEventId = $id;
        $this->isOpen = true;
        $this->loadSiswaData();
    }

    public function daftar(): void
    {
        $siswa = $this->siswa();
        if (! $siswa || ! $this->selectedEventId) return;

        // 🔒 FINAL BACKEND GUARD REGULER
        if ($msg = DaftarKejuaraanHelper::pesanErrorReguler($siswa)) {
            Notification::make()->title('Pendaftaran Ditolak')->body($msg)->danger()->send();
            return;
        }

        $useKuota = DaftarKejuaraanHelper::pakaiKuota($siswa, $this->isPakaiKuota());

        KejuaraanSiswa::create([
            'kejuaraan_id'          => $this->selectedEventId,
            'siswa_id'              => $siswa->id,
            'nama_lengkap'          => $this->data['nama_lengkap'],
            'tempat_lahir'          => $this->data['tempat_lahir'],
            'tanggal_lahir'         => $this->data['tanggal_lahir'],
            'jenis_kelamin'         => $this->data['jenis_kelamin'],
            'sabuk'                 => $this->data['sabuk'],
            'kategori_pertandingan' => $this->data['kategori_pertandingan'],
            'tageuk'                => $this->data['tageuk'] ?: null,
            'tingkat_kategori'      => $this->data['tingkat_kategori'] ?: null,
            'kategori_atlit'        => $this->data['kategori_atlit'],
            'berat_badan'           => $this->data['berat_badan'] ?: null,
            'tinggi_badan'          => $this->data['tinggi_badan'] ?: null,
            'use_kuota'             => $useKuota,
        ]);

        $this->isOpen = false;
        $this->loadTerdaftar();
        $this->hitungKuota();

        Notification::make()
            ->title('Berhasil 🎉')
            ->body($useKuota ? 'Menggunakan kuota kelas' : 'Tanpa kuota kelas')
            ->success()
            ->send();
    }

    /* -------------------------------------------------------------------------- */
    /* Util                                                                      */
    /* -------------------------------------------------------------------------- */

    private function hitungKategoriUmur(?string $tgl): string
    {
        if (! $tgl) return 'senior';

        $umur = Carbon::parse($tgl)->age;

        return match (true) {
            $umur <= 11 => 'pracadet',
            $umur <= 14 => 'cadet',
            $umur <= 17 => 'junior',
            default     => 'senior',
        };
    }
}
