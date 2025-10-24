<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Profil Saya';
    protected static string $view = 'filament.siswa.pages.profile';

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        $user = Auth::user();

        // ✅ Pastikan user memiliki name (hindari error field 'name')
        if (empty($user->name)) {
            $user->update(['name' => 'User ' . $user->id]);
        }

        $siswa = Siswa::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => $user->name ?? 'Siswa Baru',
                'email' => $user->email,
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => null,
                'golongan_darah' => null,
                'image' => null,
                'alamat_lengkap' => null,
                'no_telepon' => null,
                'nama_ayah' => null,
                'pekerjaan_ayah' => null,
                'nama_ibu' => null,
                'pekerjaan_ibu' => null,
                'kelas_id' => null,
                'current_belt_level' => 'putih',
                'beladiri_yang_pernah_diikuti' => null,
                'joint_date' => now(),
                'status' => 'aktif',
                'units_id' => null,
                'no_register' => null,
                'nis' => null,
            ]
        );

        // Fallback data kosong
        $siswa->fill([
            'jenis_kelamin' => $siswa->jenis_kelamin ?? 'Laki-laki',
            'tempat_lahir' => $siswa->tempat_lahir ?? 'Bogor',
            'current_belt_level' => $siswa->current_belt_level ?? 'putih',
            'status' => $siswa->status ?? 'aktif',
        ])->save();

        $this->data = $siswa->toArray();
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Dasar')
                ->columns(1)
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Foto Profil')
                        ->image()
                        ->avatar()
                        ->directory('profil_photos')
                        ->disk('public')
                        ->visibility('public')
                        ->maxSize(1024)
                        ->acceptedFileTypes(['image/jpeg', 'image/png'])
                        ->imageEditor()
                        ->previewable(true),

                Forms\Components\TextInput::make('nama_lengkap')
    ->label('Nama Lengkap')
    ->required()
    ->disabled(fn() => !$this->isEditing)
    ->reactive()
    ->rules(['string', 'min:3', 'max:191'])
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return;
        }

        // Validasi cepat: nama tidak boleh berupa email
        if (filter_var($state, FILTER_VALIDATE_EMAIL)) {
            \Filament\Notifications\Notification::make()
                ->title('Nama tidak valid')
                ->body('Nama tidak boleh berupa alamat email.')
                ->warning()
                ->send();
            return;
        }

        // Cari siswa yang cocok (case-insensitive)
        $matched = \App\Models\Siswa::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($state)])->first();

        if ($matched) {
            // Jika sudah terhubung ke user lain, *jika* kamu tidak ingin
            // mengambilnya otomatis, kamu bisa memeriksa matched->user_id.
            // Di sini kita akan mengaitkan matched ke user saat ini (jika belum)
            if ($matched->user_id !== $user->id) {
                // hubungkan account (tapi jangan timpa biodata)
                $matched->update([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }

            // Pastikan relasi pada model Users langsung tersedia
            $user->setRelation('siswa', $matched);

            // Ambil state sekarang untuk menentukan field mana yang kosong
            $current = $get(); // seluruh state array

            // Daftar field yang kita ingin isi otomatis jika kosong
            $fieldsToPopulate = [
                'nis',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'golongan_darah',
                'alamat_lengkap',
                'no_telepon',
                'nama_ayah',
                'pekerjaan_ayah',
                'nama_ibu',
                'pekerjaan_ibu',
                'kelas_id',
                'current_belt_level',
                'beladiri_yang_pernah_diikuti',
                'joint_date',
                'status',
                'units_id',
                'no_register',
                'image',
            ];

            foreach ($fieldsToPopulate as $field) {
                // jika field belum ada di state atau kosong/null/'' maka isi dari matched
                $valueInState = data_get($current, $field);
                $valueFromMatched = data_get($matched, $field);

                if ((is_null($valueInState) || $valueInState === '') && !is_null($valueFromMatched)) {
                    // set nilai di form tanpa mereload halaman
                    $set($field, $valueFromMatched);
                }
            }

            \Filament\Notifications\Notification::make()
                ->title('Data ditemukan')
                ->body("Data siswa '{$matched->nama_lengkap}' ditemukan dan biodata terisi otomatis.")
                ->success()
                ->send();
        } else {
            // jika tidak cocok, jangan buat data baru di sini; user akan menyimpan sendiri
            \Filament\Notifications\Notification::make()
                ->title('Tidak ditemukan')
                ->body('Tidak ada data siswa dengan nama tersebut. Akan dibuat saat Anda menyimpan profil.')
                ->info()
                ->send();
        }

        // Sinkronkan juga nama ke tabel users (agar konsisten)
        if ($user->name !== $state) {
            $user->update(['name' => $state]);
        }
    }),
                    Forms\Components\TextInput::make('nis')
                        ->label('NIS')
                        ->disabled()
                        ->dehydrated(false),
                ]),

            Forms\Components\Section::make('Biodata')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
                        ->default('Laki-laki')
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->default('Bogor')
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\Select::make('golongan_darah')
                        ->label('Golongan Darah')
                        ->options(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'])
                        ->disabled(fn() => !$this->isEditing),
                ]),

            Forms\Components\Section::make('Kontak')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('no_telepon')
                        ->label('No. Telepon')
                        ->tel()
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\Textarea::make('alamat_lengkap')
                        ->label('Alamat Lengkap')
                        ->rows(3)
                        ->columnSpanFull()
                        ->disabled(fn() => !$this->isEditing),
                ]),

            Forms\Components\Section::make('Informasi Orang Tua')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nama_ayah')->label('Nama Ayah')->disabled(fn() => !$this->isEditing),
                    Forms\Components\TextInput::make('pekerjaan_ayah')->label('Pekerjaan Ayah')->disabled(fn() => !$this->isEditing),
                    Forms\Components\TextInput::make('nama_ibu')->label('Nama Ibu')->disabled(fn() => !$this->isEditing),
                    Forms\Components\TextInput::make('pekerjaan_ibu')->label('Pekerjaan Ibu')->disabled(fn() => !$this->isEditing),
                ]),

            Forms\Components\Section::make('Akademik & Unit Latihan')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('no_register')
                        ->label('Nomor Register')
                        ->helperText('Nomor register akan tertera pada sertifikat ujian.')
                        ->placeholder('Contoh: REG-2025-001')
                        ->maxLength(50)
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\Select::make('current_belt_level')
                        ->label('Tingkatan Sabuk')
                        ->options(self::beltOptions())
                        ->default('putih')
                        ->disabled(),

                    Forms\Components\Select::make('units_id')
                        ->label('Unit Latihan')
                        ->options(Unit::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\Select::make('kelas_id')
                        ->label('Kelas')
                        ->options(Kelas::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->disabled(fn() => !auth()->user()->hasRole('admin'))
                        ->dehydrated(true)
                        ->helperText('Kelas hanya dapat diatur oleh admin.'),
                ]),

            Forms\Components\Section::make('Lain-lain')
                ->schema([
                    Forms\Components\TextInput::make('beladiri_yang_pernah_diikuti')
                        ->label('Beladiri yang Pernah Diikuti')
                        ->disabled(fn() => !$this->isEditing),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
         $user = Auth::user();
    $data = $this->form->getState();

    // sanitize minimal
    $data = collect($data)->mapWithKeys(fn($v, $k) => [$k => is_string($v) ? strip_tags($v) : $v])->toArray();

    $siswa = Siswa::updateOrCreate(['user_id' => $user->id], $data);

    // sinkron nama di users
    if (!empty($siswa->nama_lengkap)) {
        $user->update(['name' => $siswa->nama_lengkap]);
    }

    Notification::make()->title('Profil tersimpan')->success()->send();
    $this->isEditing = false;}

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

    public function edit(): void
    {
        $this->isEditing = true;
    }
}
