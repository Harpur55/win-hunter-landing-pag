@extends('StoredataSiswa.layouts.app')

@section('content')

<div class="max-w-5xl mx-auto p-6">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <strong>Periksa kembali data Anda:</strong>
            <ul class="list-disc ml-6 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="bg-indigo-600 px-6 py-4 rounded-xl mb-6 shadow-md">
    <div class="flex flex-col md:flex-row items-center justify-center gap-4">

        {{-- LOGO --}}
        <img 
            src="{{ asset('assets/images/FIX_LOGO-removebg-preview.png') }}"
            alt="Logo Taekwondo Sacti Win Hunter"
            class="
                h-16        {{-- MOBILE (lebih besar) --}}
                sm:h-18
                md:h-14     {{-- TABLET --}}
                lg:h-16     {{-- DESKTOP --}}
                w-auto 
                object-contain
            "
        >
        {{-- JUDUL --}}
        <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-white text-center">
            Form Pendataan Siswa<br class="md:hidden">
            Taekwondo Sacti Win Hunter
        </h3>

    </div>
</div>

<form method="POST" action="{{ route('siswa.store') }}">
    
        @csrf

        {{-- ID SISWA (UNTUK UPDATE) --}}
        <input type="hidden" name="siswa_id" id="siswa_id">

        {{-- ================= INFORMASI DASAR ================= --}}
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Informasi Dasar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap"
                       class="w-full mt-1 border rounded-xl p-3" autocomplete="off" required>
            </div>

           <div>
    <label>No Register</label>
    <input
        type="text"
        name="no_register"
        id="no_register"
        maxlength="18"
        inputmode="numeric"
        pattern="[0-9]*"
        class="w-full mt-1 border rounded-xl p-3"
        placeholder="Maksimal 18 digit">
</div>

            <div>
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full mt-1 border rounded-xl p-3">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div>
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="w-full mt-1 border rounded-xl p-3">
            </div>
<div>
    <label>Tanggal Lahir</label>
    <input type="date"
           name="tanggal_lahir"
           class="w-full mt-1 border rounded-xl p-3">
</div>
          <select name="current_belt_level"
        class="w-full mt-1 border rounded-xl p-3"
        required>
    <option value="">-- Pilih Sabuk --</option>

    @foreach ($beltOptions as $value => $label)
        <option value="{{ $value }}"
            {{ old('current_belt_level', $siswa->current_belt_level ?? '') == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
        </div>

        {{-- ================= ALAMAT & KONTAK ================= --}}
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Alamat & Kontak</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

           <div>
    <label class="block font-semibold mb-1 text-gray-700">
        Alamat Lengkap <span class="text-red-500">*</span>
    </label>
    <input type="text"
           name="alamat_lengkap"
           class="w-full mt-1 border rounded-xl p-3"
           placeholder="Masukkan alamat lengkap"
           required>
</div>

            <div>
                <label>No Telepon</label>
                <input type="text" name="no_telepon" class="w-full mt-1 border rounded-xl p-3">
            </div>
        </div>

      
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Informasi Orang Tua</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <input type="text" name="nama_ayah" placeholder="Nama Ayah"
                   class="w-full border rounded-xl p-3">
            <input type="text" name="pekerjaan_ayah" placeholder="Pekerjaan Ayah"
                   class="w-full border rounded-xl p-3">

            <input type="text" name="nama_ibu" placeholder="Nama Ibu"
                   class="w-full border rounded-xl p-3">
            <input type="text" name="pekerjaan_ibu" placeholder="Pekerjaan Ibu"
                   class="w-full border rounded-xl p-3">
        </div>

        {{-- ================= KELAS & UNIT ================= --}}
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Kelas & Unit</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <select name="units_id" class="w-full border rounded-xl p-3">
                <option value="">-- Pilih Unit --</option>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>

            <select name="kelas_id" class="w-full border rounded-xl p-3">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- ================= LAINNYA ================= --}}
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Lainnya</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <input type="text" name="beladiri_yang_pernah_diikuti"
                   placeholder="Beladiri yang pernah diikuti"
                   class="w-full border rounded-xl p-3">


             <div>
    <label class="block font-semibold mb-1 text-gray-700">
        Tanggal Bergabung
        <span class="text-sm text-gray-500 font-normal">
            (mulai aktif sebagai siswa)
        </span>
    </label>
    <input type="date"
           name="joint_date"
           class="w-full border rounded-xl p-3">
</div> 

            <select name="status" class="w-full border rounded-xl p-3">
                <option value="">-- Pilih Status --</option>
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="text-right">
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                Simpan Data
            </button>
        </div>
    </form>
</div>

{{-- ================= AUTOFILL SCRIPT ================= --}}
<script>
document.getElementById('nama_lengkap').addEventListener('keyup', function () {
    let nama = this.value;
    if (nama.length < 3) return;

    fetch("{{ route('siswa.search') }}?nama=" + encodeURIComponent(nama))
        .then(res => res.json())
        .then(res => {
            if (!res.found) return;

            let s = res.data;

            document.getElementById('siswa_id').value = s.id ?? '';
            document.getElementById('no_register').value = s.no_register ?? '';

            document.querySelector('[name="jenis_kelamin"]').value = s.jenis_kelamin ?? '';
            document.querySelector('[name="tempat_lahir"]').value = s.tempat_lahir ?? '';
            document.querySelector('[name="tanggal_lahir"]').value = s.tanggal_lahir ?? '';
            document.querySelector('[name="alamat_lengkap"]').value = s.alamat_lengkap ?? '';
            document.querySelector('[name="no_telepon"]').value = s.no_telepon ?? '';
            document.querySelector('[name="current_belt_level"]').value = s.current_belt_level ?? '';

            // 🔥 FIX UTAMA
            setTimeout(() => {
                document.querySelector('[name="units_id"]').value = String(s.units_id ?? '');
                document.querySelector('[name="kelas_id"]').value = String(s.kelas_id ?? '');
            }, 50);

            document.querySelector('[name="nama_ayah"]').value = s.nama_ayah ?? '';
            document.querySelector('[name="pekerjaan_ayah"]').value = s.pekerjaan_ayah ?? '';
            document.querySelector('[name="nama_ibu"]').value = s.nama_ibu ?? '';
            document.querySelector('[name="pekerjaan_ibu"]').value = s.pekerjaan_ibu ?? '';
        })
        .catch(console.error);
});

function clearFields() {
    document.querySelectorAll('input, select, textarea')
        .forEach(el => el.value = '');
}
</script>
@endsection
