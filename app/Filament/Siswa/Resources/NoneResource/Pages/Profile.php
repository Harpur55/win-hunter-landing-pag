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
        $siswa = Siswa::where('user_id', $user->id)->first();

        // ✅ Jika belum punya data siswa, buat default baru
        if (!$siswa) {
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
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
            ]);
        }

        // ✅ Default fallback jika kosong
        if (empty($siswa->jenis_kelamin)) $siswa->jenis_kelamin = 'Laki-laki';
        if (empty($siswa->tempat_lahir)) $siswa->tempat_lahir = 'Bogor';
        if (empty($siswa->current_belt_level)) $siswa->current_belt_level = 'putih';
        $siswa->save();

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
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $existing = \App\Models\Siswa::where('nama_lengkap', $state)
                                ->where('user_id', '!=', Auth::id())
                                ->first();

                            if ($existing) {
                                $fields = [
                                    'jenis_kelamin',
                                    'tempat_lahir',
                                    'tanggal_lahir',
                                    'golongan_darah',
                                    'no_telepon',
                                    'alamat_lengkap',
                                    'nama_ayah',
                                    'pekerjaan_ayah',
                                    'nama_ibu',
                                    'pekerjaan_ibu',
                                    'beladiri_yang_pernah_diikuti',
                                    'units_id',
                                    'kelas_id',
                                ];

                                foreach ($fields as $field) {
                                    if ($existing->{$field} && empty($get($field))) {
                                        $set($field, $existing->{$field});
                                    }
                                }

                                Notification::make()
                                    ->title('Data Otomatis Terisi')
                                    ->body('Data otomatis dilengkapi dari nama yang sudah ada di sistem.')
                                    ->info()
                                    ->send();
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
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->default('Laki-laki')
                        ->required()
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
                        ->default('Bogor')
                        ->required()
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
                    Forms\Components\TextInput::make('nama_ayah')
                        ->label('Nama Ayah')
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\TextInput::make('pekerjaan_ayah')
                        ->label('Pekerjaan Ayah')
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\TextInput::make('nama_ibu')
                        ->label('Nama Ibu')
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\TextInput::make('pekerjaan_ibu')
                        ->label('Pekerjaan Ibu')
                        ->disabled(fn() => !$this->isEditing),
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
                        ->disabled(fn() => ! auth()->user()->hasRole('admin'))
                        ->dehydrated(true)
                        ->helperText('Kelas hanya dapat diatur oleh admin.')
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

        $siswa = Siswa::updateOrCreate(['user_id' => $user->id], $data);

        // Update nama user di tabel users juga
        if (!empty($siswa->nama_lengkap)) {
            $user->name = $siswa->nama_lengkap;
            $user->save();
        }

        // ✅ Pastikan nilai default tetap ada
        $siswa->tempat_lahir ??= 'Bogor';
        $siswa->jenis_kelamin ??= 'Laki-laki';
        $siswa->current_belt_level ??= 'putih';
        $siswa->status ??= 'aktif';
        $siswa->save();

        $this->isEditing = false;

        Notification::make()
            ->title('Profil Berhasil Disimpan')
            ->success()
            ->body('Profil telah diperbarui. Nomor register digunakan untuk sertifikat ujian.')
            ->send();
    }

    private static function beltOptions(): array
    {
        return [
            'putih'               => 'Putih',
            'kuning'              => 'Kuning',
            'kuning strip hijau'  => 'Kuning Strip Hijau',
            'hijau'               => 'Hijau',
            'hijau strip biru'    => 'Hijau Strip Biru',
            'biru'                => 'Biru',
            'biru strip merah'    => 'Biru Strip Merah',
            'merah'               => 'Merah',
            'merah strip hitam 1' => 'Merah Strip Hitam 1',
            'merah strip hitam 2' => 'Merah Strip Hitam 2',
            'hitam'               => 'Hitam',
        ];
    }
    public function edit(): void
{
    $this->isEditing = true;
}
}
