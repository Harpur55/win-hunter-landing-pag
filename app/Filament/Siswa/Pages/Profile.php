<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;
use Filament\Pages\Page;
use Filament\Forms;
use App\Helpers\NameHelper;

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
    public bool $isEditing = true;

    // Jika data kunci sudah lengkap → lock no_register & unit
    public bool $lockKeyFields = false;

    public function mount(): void
    {
        $user = Auth::user();

        // Buat atau ambil data siswa
        $siswa = Siswa::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => $user->name,
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => now()->toDateString(),
                'units_id' => 1,
                'kelas_id' => 1,
                'current_belt_level' => 'Putih',
                'status' => 'Aktif',
            ]
        );

        // Lock jika 3 data kunci sudah ada
        $this->lockKeyFields =
            $siswa->no_register &&
            $siswa->tanggal_lahir &&
            $siswa->units_id;

        $this->data = $siswa->toArray();
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Identitas Dasar & Akademik')
                    ->columns(2)
                    ->schema([

                        Forms\Components\FileUpload::make('image')
                            ->avatar()
                            ->directory('profil_photos')
                            ->disk('public')
                            ->maxSize(1024)
                            ->imageEditor()
                            ->previewable(true)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('nama_lengkap')
                            ->required()
                            ->disabled(fn () => !$this->isEditing),

                        Forms\Components\TextInput::make('no_register')
                            ->label('No Register')
                            ->required()
                            ->disabled(fn () => !$this->isEditing || $this->lockKeyFields)
                            ->afterStateUpdated(function ($state, callable $set, $get) {

                                if (!$state) return;

                                $inputNama = NameHelper::normalize($get('nama_lengkap'));
                                $tanggal   = $get('tanggal_lahir');
                                $unit      = $get('units_id');

                                $siswa = Siswa::where('no_register', $state)->first();

                                if ($siswa) {

                                    $matchNama = strtolower($siswa->nama_lengkap) == strtolower($inputNama);
                                    $matchTgl  = $tanggal && $siswa->tanggal_lahir == $tanggal;
                                    $matchUnit = $unit && $siswa->units_id == $unit;

                                    if ($matchNama && $matchTgl && $matchUnit) {

                                        $set('nama_lengkap', $siswa->nama_lengkap);
                                        $set('tempat_lahir', $siswa->tempat_lahir);
                                        $set('jenis_kelamin', $siswa->jenis_kelamin);
                                        $set('kelas_id', $siswa->kelas_id);
                                        $set('current_belt_level', $siswa->current_belt_level);
                                        $set('nis', $siswa->nis);
                                        $set('golongan_darah', $siswa->golongan_darah);
                                        $set('alamat_lengkap', $siswa->alamat_lengkap);
                                        $set('no_telepon', $siswa->no_telepon);

                                        Notification::make()
                                            ->title('Data ditemukan')
                                            ->body('Semua data otomatis terisi.')
                                            ->success()
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Data tidak cocok')
                                            ->body('Nama, tanggal lahir atau unit salah.')
                                            ->warning()
                                            ->send();
                                    }
                                } else {
                                    Notification::make()
                                        ->title('Nomor belum terdaftar')
                                        ->body('Akan membuat data siswa baru.')
                                        ->info()
                                        ->send();
                                }
                            }),

                        Forms\Components\Select::make('units_id')
                            ->label('Unit')
                            ->options(Unit::pluck('name', 'id'))
                            ->required()
                            ->disabled(fn () => !$this->isEditing || $this->lockKeyFields),

                        Forms\Components\TextInput::make('tempat_lahir')
                            ->disabled(fn () => !$this->isEditing),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->required()
                            ->disabled(fn () => !$this->isEditing),

                        Forms\Components\TextInput::make('nis')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('current_belt_level')
                            ->options(self::beltOptions())
                            ->disabled(), // LOCKED ALWAYS

                        Forms\Components\Select::make('kelas_id')
                            ->options(Kelas::pluck('name', 'id'))
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Biodata')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
                            ->disabled(fn () => !$this->isEditing),

                        Forms\Components\Select::make('golongan_darah')
                            ->options(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'])
                            ->disabled(fn () => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_telepon')
                            ->tel()
                            ->disabled(fn () => !$this->isEditing),

                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->rows(3)
                            ->disabled(fn () => !$this->isEditing)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Informasi Orang Tua')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ayah')->disabled(fn () => !$this->isEditing),
                        Forms\Components\TextInput::make('pekerjaan_ayah')->disabled(fn () => !$this->isEditing),
                        Forms\Components\TextInput::make('nama_ibu')->disabled(fn () => !$this->isEditing),
                        Forms\Components\TextInput::make('pekerjaan_ibu')->disabled(fn () => !$this->isEditing),
                    ]),

                Forms\Components\Section::make('Lain-lain')
                    ->schema([
                        Forms\Components\TextInput::make('beladiri_yang_pernah_diikuti')
                            ->disabled(fn () => !$this->isEditing),
                    ]),
            ])
            ->statePath('data');
    }

    // ============================
    // SAVE LOGIC
    // ============================
    public function save(): void
    {
        $user = Auth::user();
        $data = $this->form->getState();

        if (!empty($data['nama_lengkap'])) {
            $data['nama_lengkap'] = NameHelper::normalize($data['nama_lengkap']);
        }

        $data['jenis_kelamin'] = $data['jenis_kelamin'] ?? 'Laki-laki';
        $data['tempat_lahir'] = $data['tempat_lahir'] ?? 'Bogor';
        $data['tanggal_lahir'] = $data['tanggal_lahir'] ?? now()->toDateString();
        $data['units_id'] = $data['units_id'] ?? 1;
        $data['kelas_id'] = $data['kelas_id'] ?? 1;

        if (
            empty($data['no_register']) ||
            empty($data['tanggal_lahir']) ||
            empty($data['units_id'])
        ) {
            Notification::make()
                ->title('Data kunci tidak lengkap')
                ->danger()
                ->send();
            return;
        }

        $existing = Siswa::where('no_register', $data['no_register'])->first();

        if ($existing) {

            if ($existing->user_id !== null && $existing->user_id !== $user->id) {
                Notification::make()
                    ->title('Nomor dipakai orang lain')
                    ->danger()
                    ->send();
                return;
            }

            $existing->update($data);
            $user->update(['name' => $existing->nama_lengkap]);

            Notification::make()
                ->title('Profil diperbarui')
                ->success()
                ->send();

            $this->lockKeyFields = true;
            $this->isEditing = false;
            $this->form->fill($existing->toArray());
            return;
        }

        $new = Siswa::create(array_merge($data, [
            'user_id' => $user->id,
        ]));

        $user->update(['name' => $new->nama_lengkap]);

        Notification::make()
            ->title('Data siswa baru dibuat')
            ->success()
            ->send();

        $this->lockKeyFields = true;
        $this->isEditing = false;
        $this->form->fill($new->toArray());
    }

    public static function beltOptions(): array
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