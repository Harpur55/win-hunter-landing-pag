@if (session('success'))
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-blue-600 text-white p-4 rounded-lg mb-6">
    <h2 class="text-xl font-bold">
        {{ $mode == 'create' ? 'Daftar Ujian:' : 'Edit Pendaftaran:' }}
        {{ $eventUjian->nama_ujian }}
    </h2>
</div>

@if ($mode == 'edit')
    <div class="mb-4">
        <a href="{{ route('ujian.hasil', $pendaftaran->id) }}" class="bg-green-600 px-4 py-2 text-white rounded shadow">
            Lihat Hasil Ujian
        </a>
    </div>
@endif

<form
    action="{{ $mode == 'create'
        ? route('ujian.daftar.store', $eventUjian->slug)
        : route('ujian.daftar.update', [$eventUjian->slug, $pendaftaran->id]) }}"
    method="POST" class="space-y-6">
    @csrf

    {{-- NAMA SISWA --}}
    <div>
        <label class="font-semibold">Nama Siswa *</label>
        <select id="siswa_id" name="siswa_id"
            class="w-full p-3 border rounded @error('siswa_id') border-red-500 @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach ($siswas as $s)
                <option value="{{ $s->id }}"
                    {{ old('siswa_id', $pendaftaran->siswa_id ?? '') == $s->id ? 'selected' : '' }}>
                    {{ $s->nama_lengkap }}
                </option>
            @endforeach
        </select>
        @error('siswa_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label>Jenis Kelamin *</label>
        <select name="jenis_kelamin" class="w-full p-3 border rounded @error('jenis_kelamin') border-red-500 @enderror"
            required>
            <option value="">-- Pilih --</option>
            <option value="L"
                {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>
                Laki-laki
            </option>
            <option value="P"
                {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>
                Perempuan
            </option>
        </select>
        @error('jenis_kelamin')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- TEMPAT LAHIR --}}
    <div>
        <label>Tempat Lahir *</label>
        <input type="text" name="tempat_lahir"
            class="w-full p-3 border rounded @error('tempat_lahir') border-red-500 @enderror"
            value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}" required>
        @error('tempat_lahir')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- TANGGAL LAHIR --}}
    <div>
        <label>Tanggal Lahir *</label>
        <input type="date" name="tanggal_lahir"
            class="w-full p-3 border rounded @error('tanggal_lahir') border-red-500 @enderror"
            value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ?? '') }}" required>
        @error('tanggal_lahir')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- NOMOR REGISTER --}}
    <div>
        <label>No Register *</label>
        <input type="text" name="no_register"
            class="w-full p-3 border rounded @error('no_register') border-red-500 @enderror"
            value="{{ old('no_register', $pendaftaran->no_register ?? '') }}" required>
        @error('no_register')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- SABUK SEKARANG --}}
    <div>
        <label>Sabuk Sekarang *</label>
        <select name="current_belt_level"
            class="w-full p-3 border rounded @error('current_belt_level') border-red-500 @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach ($sabukList as $key => $label)
                <option value="{{ $key }}"
                    {{ old('current_belt_level', $pendaftaran->current_belt_level ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('current_belt_level')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- SABUK BERIKUTNYA --}}
    <div>
        <label>Sabuk Berikutnya *</label>
        <select name="next_belt_level"
            class="w-full p-3 border rounded @error('next_belt_level') border-red-500 @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach ($sabukList as $key => $label)
                <option value="{{ $key }}"
                    {{ old('next_belt_level', $pendaftaran->next_belt_level ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('next_belt_level')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- UNIT --}}
    <div>
        <label>Unit *</label>
        <select name="units_id" class="w-full p-3 border rounded @error('units_id') border-red-500 @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach ($units as $u)
                <option value="{{ $u->id }}"
                    {{ old('units_id', $pendaftaran->units_id ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
        @error('units_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- KELAS --}}
    <div>
        <label>Kelas *</label>
        <select name="kelas_id" class="w-full p-3 border rounded @error('kelas_id') border-red-500 @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->id }}"
                    {{ old('kelas_id', $pendaftaran->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                    {{ $k->name }}
                </option>
            @endforeach
        </select>
        @error('kelas_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg">
        {{ $mode == 'create' ? 'Daftar Ujian' : 'Update Pendaftaran' }}
    </button>
</form>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
    $('#siswa_id').select2();

    $('#siswa_id').on('change', function() {
        let id = $(this).val();
        if (!id) return;

        $.get("/api/siswa/" + id, function(data) {
            console.log('Data siswa:', data);

            $('input[name="tempat_lahir"]').val(data.tempat_lahir ?? '');
            $('input[name="tanggal_lahir"]').val(data.tanggal_lahir ?? '');
            $('input[name="no_register"]').val(data.no_register ?? '');

            $('select[name="current_belt_level"]')
                .val(data.current_belt_level ?? '')
                .trigger('change');

            $('select[name="next_belt_level"]')
                .val(data.next_belt_level ?? '')
                .trigger('change');

            $('select[name="units_id"]')
                .val(data.units_id ?? '')
                .trigger('change');

            $('select[name="kelas_id"]')
                .val(data.kelas_id ?? '')
                .trigger('change');
        });
    });
</script>
