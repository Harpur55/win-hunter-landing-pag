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

        // Pastikan user punya nama minimal
        if (empty($user->name)) {
            $user->update(['name' => 'User ' . $user->id]);
        }

        // Ambil data siswa berdasarkan user_id (jika ada)
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            // Coba cari siswa berdasarkan nama (case-insensitive)
            $matched = Siswa::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($user->name)])->first();

            if ($matched) {
                // Hubungkan user ke siswa yang sudah ada
                $matched->update([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                $siswa = $matched;

                Notification::make()
                    ->title('Akun berhasil dikaitkan')
                    ->body("Data siswa '{$matched->nama_lengkap}' berhasil dikaitkan dengan akun Google Anda.")
                    ->success()
                    ->send();
            } else {
                // Jika tidak ada, jangan buat langsung — biarkan nanti saat simpan
                $siswa = new Siswa([
                    'nama_lengkap' => $user->name,
                    'email' => $user->email,
                ]);
            }
        }

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
                            $user = Auth::user();
                            if (!$user) return;

                            // Abaikan jika nama berupa email
                            if (filter_var($state, FILTER_VALIDATE_EMAIL)) {
                                Notification::make()
                                    ->title('Nama tidak valid')
                                    ->body('Nama tidak boleh berupa alamat email.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            // Cek data siswa dengan nama yang sama (case-insensitive)
                            $matched = Siswa::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($state)])
                                ->where(function ($q) use ($user) {
                                    $q->whereNull('user_id')->orWhere('user_id', $user->id);
                                })
                                ->first();

                            if ($matched) {
                                // Hubungkan user ke siswa
                                $matched->update([
                                    'user_id' => $user->id,
                                    'email' => $user->email,
                                ]);

                                $user->setRelation('siswa', $matched);

                                $current = $get();

                                // Isi otomatis beberapa field kosong dari data siswa lama
                                $fields = [
                                    'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                                    'current_belt_level', 'no_register',
                                ];

                                foreach ($fields as $field) {
                                    $currentValue = data_get($current, $field);
                                    $matchedValue = data_get($matched, $field);

                                    if ((is_null($currentValue) || $currentValue === '') && !is_null($matchedValue)) {
                                        $set($field, $matchedValue);
                                    }
                                }

                                Notification::make()
                                    ->title('Data siswa ditemukan')
                                    ->body("Data '{$matched->nama_lengkap}' berhasil dikaitkan dan biodata diisi otomatis.")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Tidak ditemukan')
                                    ->body('Nama tidak cocok dengan data siswa manapun. Data baru akan dibuat saat disimpan.')
                                    ->info()
                                    ->send();
                            }

                            // Sinkron nama user
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
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\TextInput::make('tempat_lahir')
                        ->label('Tempat Lahir')
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
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('no_register')
                        ->label('Nomor Register')
                        ->helperText('Nomor register akan tertera pada sertifikat ujian.')
                        ->maxLength(50)
                        ->disabled(fn() => !$this->isEditing),

                    Forms\Components\Select::make('current_belt_level')
                        ->label('Tingkatan Sabuk')
                        ->options(self::beltOptions())
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
                        ->disabled(fn() => !auth()->user()->hasRole('admin')),

                    Forms\Components\TextInput::make('status')
                        ->label('Status Siswa')
                        ->default('Aktif')
                        ->disabled(fn() => !auth()->user()->hasRole('admin')),
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
        $data = collect($this->form->getState())
            ->mapWithKeys(fn($v, $k) => [$k => is_string($v) ? strip_tags($v) : $v])
            ->toArray();

        // Jika user belum punya siswa, baru buat
        $siswa = Siswa::firstOrCreate(['user_id' => $user->id], $data);

        // Sinkron nama user
        if (!empty($siswa->nama_lengkap)) {
            $user->update(['name' => $siswa->nama_lengkap]);
        }

        Notification::make()->title('Profil tersimpan')->success()->send();
        $this->isEditing = false;
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

    public function edit(): void
    {
        $this->isEditing = true;
    }
}
