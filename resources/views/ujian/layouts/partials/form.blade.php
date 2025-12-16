@if(session('success'))
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        {{ session('success') }}
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
    <a href="{{ route('ujian.hasil', $pendaftaran->id) }}"
       class="bg-green-600 px-4 py-2 text-white rounded shadow">
        Lihat Hasil Ujian
    </a>
</div>
@endif

<form action="{{ $mode=='create'
        ? route('ujian.daftar.store', $eventUjian->id)
        : route('ujian.daftar.update', [$eventUjian->id, $pendaftaran->id]) }}"
      method="POST" class="space-y-6">

    @csrf

    <!-- NAMA SISWA -->
    <div>
        <label class="font-semibold">Nama Siswa *</label>
        <select id="siswa_id" name="siswa_id" class="w-full p-3 border rounded" required>
            <option value="">-- Pilih --</option>
            @foreach($siswas as $s)
                <option value="{{ $s->id }}"
                    {{ old('siswa_id', $pendaftaran->siswa_id ?? '') == $s->id ? 'selected' : '' }}>
                    {{ $s->nama_lengkap }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- TEMPAT LAHIR -->
    <div>
        <label>Tempat Lahir *</label>
        <input type="text" name="tempat_lahir"
               class="w-full p-3 border rounded"
               value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}" required>
    </div>

    <!-- TANGGAL LAHIR -->
    <div>
        <label>Tanggal Lahir *</label>
        <input type="date" name="tanggal_lahir"
               class="w-full p-3 border rounded"
               value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ?? '') }}" required>
    </div>

    <!-- NOMOR REGISTER -->
    <div>
        <label>No Register *</label>
        <input type="text" name="no_register"
               class="w-full p-3 border rounded"
               value="{{ old('no_register', $pendaftaran->no_register ?? '') }}" required>
    </div>

    <!-- SABUK SEKARANG -->
    <div>
        <label>Sabuk Sekarang *</label>
        <select name="current_belt_level" class="w-full p-3 border rounded" required>
            <option value="">-- Pilih --</option>
            @foreach($sabukList as $key => $label)
                <option value="{{ $key }}"
                    {{ old('current_belt_level', $pendaftaran->current_belt_level ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- SABUK BERIKUTNYA -->
    <div>
        <label>Sabuk Berikutnya *</label>
        <select name="next_belt_level" class="w-full p-3 border rounded" required>
            <option value="">-- Pilih --</option>
            @foreach($sabukList as $key => $label)
                <option value="{{ $key }}"
                    {{ old('next_belt_level', $pendaftaran->next_belt_level ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- UNIT -->
    <div>
        <label>Unit *</label>
        <select name="units_id" class="w-full p-3 border rounded" required>
            <option value="">-- Pilih --</option>
            @foreach($units as $u)
                <option value="{{ $u->id }}"
                    {{ old('units_id', $pendaftaran->units_id ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- KELAS -->
    <div>
        <label>Kelas *</label>
        <select name="kelas_id" class="w-full p-3 border rounded" required>
            <option value="">-- Pilih --</option>
            @foreach($kelas as $k)
                <option value="{{ $k->id }}"
                    {{ old('kelas_id', $pendaftaran->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                    {{ $k->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg">
        {{ $mode=='create' ? 'Daftar Ujian' : 'Update Pendaftaran' }}
    </button>

</form>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$('#siswa_id').select2();

$('#siswa_id').on('change', function () {
    let id = $(this).val();
    if (!id) return;

    $.get("/api/siswa/" + id, function (data) {
        $('input[name="tempat_lahir"]').val(data.tempat_lahir);
        $('input[name="tanggal_lahir"]').val(data.tanggal_lahir);
        $('input[name="no_register"]').val(data.no_register);
        $('select[name="current_belt_level"]').val(data.current_belt_level).trigger('change');
        $('select[name="next_belt_level"]').val(data.next_belt_level).trigger('change');
        $('select[name="units_id"]').val(data.units_id).trigger('change');
        $('select[name="kelas_id"]').val(data.kelas_id).trigger('change');
    });
});
</script>
