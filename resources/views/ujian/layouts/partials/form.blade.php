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
        <div class="mb-6 relative">
    <label class="font-semibold mb-2 block">Cari Nama Siswa *</label>

    <input id="search"
           type="text"
           autocomplete="off"
           placeholder="Ketik nama siswa..."
           class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-400"
           required>

    <input type="hidden" name="siswa_id" id="siswa_id">

    <div id="dropdown"
         class="absolute w-full bg-white border rounded shadow-lg mt-1 hidden max-h-60 overflow-y-auto z-50">
    </div>

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
const SISWA = @json($siswaJson);

const search   = document.getElementById('search');
const dropdown = document.getElementById('dropdown');
const siswaId  = document.getElementById('siswa_id');

function pilihSiswa(s) {
    siswaId.value = s.id;
    search.value  = s.nama;

    // isi otomatis (sama seperti API sebelumnya)
    document.querySelector('input[name="tempat_lahir"]').value  = s.tempat_lahir ?? '';
    document.querySelector('input[name="tanggal_lahir"]').value = s.tanggal_lahir ?? '';
    document.querySelector('input[name="no_register"]').value   = s.no_register ?? '';

    document.querySelector('select[name="current_belt_level"]').value = s.current_belt_level ?? '';
    document.querySelector('select[name="next_belt_level"]').value    = s.next_belt_level ?? '';
    document.querySelector('select[name="units_id"]').value           = s.units_id ?? '';
    document.querySelector('select[name="kelas_id"]').value           = s.kelas_id ?? '';

    dropdown.classList.add('hidden');
}

search.addEventListener('input', function () {
    const key = this.value.toLowerCase();
    dropdown.innerHTML = '';

    if (key.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }

    const hasil = SISWA
        .filter(s => s.nama.toLowerCase().includes(key))
        .slice(0, 6);

    if (!hasil.length) {
        dropdown.classList.add('hidden');
        return;
    }

    hasil.forEach(s => {
        const div = document.createElement('div');
        div.className = 'px-4 py-3 cursor-pointer hover:bg-blue-100';
        div.innerHTML = `
            <b>${s.nama}</b>
            <div class="text-sm text-gray-500">${s.unit}</div>
        `;
        div.onclick = () => pilihSiswa(s);
        dropdown.appendChild(div);
    });

    dropdown.classList.remove('hidden');
});
</script>

