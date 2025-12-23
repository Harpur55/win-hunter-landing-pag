<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Siswa;
use App\Models\Kejuaraan;
use App\Models\KejuaraanSiswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DaftarKejuaraan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'KEJUARAAN';
    protected static ?string $navigationLabel = 'Daftar Kejuaraan';
    protected static string $view = 'filament.siswa.pages.daftar-kejuaraan';
    protected static ?string $slug = 'daftar-kejuaraan';

    public Collection $events;
    public array $data = [];
    public bool $isOpen = false;
    public ?int $selectedEventId = null;
    public array $sudahTerdaftar = [];
    public int $kuotaMaks = 0;
    public int $kuotaTerpakai = 0;
    public bool $kuotaHabis = false;
    public array $sudahDapatMedali = [];

    // ✅ Tambahan: flag pakai kuota
    public bool $pakaiKuota = true;

    public function mount(): void
    {
        $this->loadEvents();      // ✅ hanya kejuaraan tahun ini & depan
        $this->loadSiswaData();
        $this->loadTerdaftar();
        $this->loadMedali();
        $this->hitungKuota();     // ✅ Sekarang hitung akurat

        // Hilangkan badge
        session(['kejuaraan_seen' => true]);

        // Notifikasi umum
        $this->checkNotifications();
    }

    /** ===============================
     *  Ambil Kejuaraan Tahun Ini & Tahun Depan
     *  =============================== */
    private function loadEvents(): void
    {
        $tahunSekarang = Carbon::now()->year;
        $tahunDepan = $tahunSekarang + 1;

        $this->events = Kejuaraan::query()
            ->whereYear('tanggal_mulai', '>=', $tahunSekarang)
            ->whereYear('tanggal_mulai', '<=', $tahunDepan)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
    }

    /** ===============================
     *  Cek dan Kirim Notifikasi
     *  =============================== */
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

        if ($this->events->every(fn($event) => $event->is_registration_closed)) {
            Notification::make()
                ->title('Pendaftaran Ditutup ⛔')
                ->body('Semua kejuaraan saat ini sedang ditutup oleh panitia.')
                ->warning()
                ->send();
        }

        // ✅ Notifikasi hanya jika kuota BENAR-BENAR habis
        if ($this->kuotaHabis) {
            Notification::make()
                ->title('Kuota Kamu Sudah Habis 🎯')
                ->body('Kamu telah menggunakan seluruh kuota kejuaraan yang tersedia untuk kelasmu.')
                ->warning()
                ->send();
        }
    }

    /** ===============================
     *  ✅ HITUNG KUOTA AKURAT - SELARAS DENGAN WIDGET
     *  =============================== */
    private function hitungKuota(): void
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return;

        // ✅ HITUNG ULANG dari kejuaraan_siswa (sama seperti widget)
        $totalPendaftaranPakaiKuota = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->where('use_kuota', true)
            ->count();

        $kuotaAwal = $siswa->kelas?->kuota_awal ?? 0;
        $sisaKuotaReal = max(0, $kuotaAwal - $totalPendaftaranPakaiKuota);

        // ✅ SYNC database jika ada perbedaan
        if (($siswa->sisa_kuota ?? 0) != $sisaKuotaReal) {
            $siswa->update(['sisa_kuota' => $sisaKuotaReal]);
        }

        // ✅ Set properti tampilan
        $this->kuotaMaks     = $kuotaAwal;
        $this->kuotaTerpakai = $totalPendaftaranPakaiKuota;
        $this->kuotaHabis    = $sisaKuotaReal <= 0;
    }

    /** ===============================
     *  Load Data Kejuaraan yang Diikuti
     *  =============================== */
    private function loadTerdaftar(): void
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return;

        $this->sudahTerdaftar = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->pluck('kejuaraan_id')
            ->toArray();
    }

    /** ===============================
     *  Load Data Siswa
     *  =============================== */
    private function loadSiswaData(): void
    {
        $siswa = Auth::user()->siswa;

        if ($siswa) {
            $this->data = [
                'nama_lengkap'        => $siswa->nama_lengkap,
                'tempat_lahir'        => $siswa->tempat_lahir,
                'tanggal_lahir'       => $siswa->tanggal_lahir
                    ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')
                    : null,
                'jenis_kelamin'       => $siswa->jenis_kelamin,
                'sabuk'               => $siswa->current_belt_level,
                'no_register'         => $siswa->no_register ?? null,
                'kategori_atlit'      => $this->hitungKategoriUmur($siswa->tanggal_lahir),
                'kategori_pertandingan' => '',
                'tageuk'              => '',
                'tingkat_kategori'    => '',
                'berat_badan'         => '',
                'tinggi_badan'        => '',
            ];
        }

        // reset pilihan pakai kuota setiap buka / batal form
        $this->pakaiKuota = true;
    }

    /** ===============================
     *  Buka Form Pendaftaran
     *  =============================== */
    public function openForm(int $id): void
    {
        // ✅ Hanya blok jika PILIH pakai kuota DAN kuota habis
        if ($this->pakaiKuota && $this->kuotaHabis) {
            Notification::make()
                ->title('Kuota Kamu Sudah Habis 🎯')
                ->body('Kamu tidak bisa mendaftar kejuaraan baru **menggunakan kuota kelas**. Pilih "Tidak gunakan kuota" jika ingin tetap daftar.')
                ->warning()
                ->send();
            return;
        }

        $event = Kejuaraan::find($id);

        if (!$event) {
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
        $this->isOpen = true;
        $this->loadSiswaData();
    }

    /** ===============================
     *  Batal Isi Form
     *  =============================== */
    public function batal(): void
    {
        $this->isOpen = false;
        $this->loadSiswaData();
    }

    /** ===============================
     *  Simpan Pendaftaran
     *  =============================== */
    public function daftar(): void
    {
        $siswa = Auth::user()->siswa;

        // ✅ Validasi field wajib
        if (empty($this->data['kategori_pertandingan'])) {
            Notification::make()
                ->title('⚠️ Kategori pertandingan harus dipilih!')
                ->warning()
                ->send();
            return;
        }

        // jika memilih pakai kuota dan kuota habis → blok
        if ($this->pakaiKuota && $this->kuotaHabis) {
            Notification::make()
                ->title('Kuota Kamu Sudah Habis 🎯')
                ->body('Pilih "Tidak gunakan kuota" untuk tetap bisa daftar.')
                ->warning()
                ->send();
            return;
        }

        if (!$siswa || !$this->selectedEventId) {
            Notification::make()->title('Terjadi kesalahan.')->danger()->send();
            return;
        }

        $event = Kejuaraan::find($this->selectedEventId);
        if (!$event) {
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

        // ✅ simpan pendaftaran TANPA ubah kuota di sini
        KejuaraanSiswa::create([
            'kejuaraan_id'        => $this->selectedEventId,
            'siswa_id'            => $siswa->id,
            'nama_lengkap'        => $this->data['nama_lengkap'],
            'tempat_lahir'        => $this->data['tempat_lahir'],
            'tanggal_lahir'       => $this->data['tanggal_lahir'],
            'jenis_kelamin'       => $this->data['jenis_kelamin'],
            'sabuk'               => $this->data['sabuk'],
            'kategori_pertandingan' => $this->data['kategori_pertandingan'],
            'tageuk'              => $this->data['tageuk'] ?: null,
            'tingkat_kategori'    => $this->data['tingkat_kategori'] ?: null,
            'kategori_atlit'      => $this->data['kategori_atlit'],
            'berat_badan'         => $this->data['berat_badan'] ?: null,
            'tinggi_badan'        => $this->data['tinggi_badan'] ?: null,
            // flag pakai kuota (butuh kolom di DB)
            'use_kuota'         => $this->useKuota,
        ]);

        // ✅ Kuota akan dihitung ulang otomatis oleh hitungKuota()
        $this->isOpen = false;
        $this->loadTerdaftar();
        $this->hitungKuota(); // ✅ Ini yang sync dengan widget

        $statusKuota = $this->pakaiKuota ? '✅ Menggunakan kuota kelas' : '➡️ Tanpa kuota kelas';
        Notification::make()
            ->title('Kamu telah terdaftar di kejuaraan ini 🎉')
            ->body("Kategori: " . strtoupper($this->data['kategori_pertandingan']) . ". {$statusKuota}")
            ->success()
            ->send();
    }

    /** ===============================
     *  Hitung Kategori Umur
     *  =============================== */
    private function hitungKategoriUmur(?string $tanggalLahir): string
    {
        if (!$tanggalLahir) return 'senior';
        $umur = Carbon::parse($tanggalLahir)->age;

        return match (true) {
            $umur <= 11 => 'pracadet',
            $umur <= 14 => 'cadet',
            $umur <= 17 => 'junior',
            default     => 'senior',
        };
    }

    /** ===============================
     *  Medali
     *  =============================== */
    private function loadMedali(): void
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return;

        $this->sudahDapatMedali = KejuaraanSiswa::where('siswa_id', $siswa->id)
            ->whereNotNull('medali')
            ->pluck('kejuaraan_id')
            ->toArray();
    }

    /** ===============================
     *  Batalkan Pendaftaran
     *  =============================== */
    public function batalDaftar($eventId): void
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return;

        $record = KejuaraanSiswa::where('kejuaraan_id', $eventId)
            ->where('siswa_id', $siswa->id)
            ->first();

        if (!$record) {
            return;
        }

        // ✅ Kuota akan dihitung ulang otomatis oleh hitungKuota()
        $record->delete();

        $this->loadTerdaftar();
        $this->hitungKuota(); // ✅ Ini yang sync dengan widget

        Notification::make()
            ->title('Pendaftaran dibatalkan')
            ->body('Kuota kelas akan otomatis disesuaikan.')
            ->success()
            ->send();
    }

    /** ===============================
     *  Ambil Medali Berdasarkan Event
     *  =============================== */
    public function getMedaliByEventId($eventId): ?string
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) return null;

        $riwayat = KejuaraanSiswa::where('kejuaraan_id', $eventId)
            ->where('siswa_id', $siswa->id)
            ->first();

        return $riwayat?->medali;
    }

    /** ===============================
     *  Badge Navigation
     *  =============================== */
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if (!$user || !$user->siswa) return null;
        $siswa = $user->siswa;

        if (session('kejuaraan_seen')) return null;

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
