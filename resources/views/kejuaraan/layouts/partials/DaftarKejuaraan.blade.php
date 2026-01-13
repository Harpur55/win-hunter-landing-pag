<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Kejuaraan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-100 via-white to-blue-50 py-10">

<div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-6 md:p-10">

    <!-- HEADER -->
   <div class="mb-10 rounded-2xl bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 p-5 sm:p-6 text-white shadow-lg">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5">

        <!-- LOGO CLUB -->
        <div class="mx-auto sm:mx-0 flex h-20 w-20 sm:h-24 sm:w-24 items-center justify-center rounded-full 
                    bg-white shadow-md ring-4 ring-white/30 overflow-hidden">
            <img
                src="https://www.win-hunter.com/assets/images/download.jpg"
                alt="Logo Sacti Win-Hunter"
                class="h-full w-full object-cover rounded-full"
                onerror="this.style.display='none'"
            >
        </div>

        <!-- TEXT -->
        <div class="text-center sm:text-left">
            <p class="text-xs sm:text-sm uppercase tracking-widest text-blue-200">
                Sacti Win-Hunter
            </p>

            <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold leading-tight">
                Form Pendaftaran Kejuaraan
            </h1>

            <p class="mt-1 text-sm sm:text-lg font-semibold text-blue-100">
                {{ $kejuaraan->nama_kejuaraan }}
            </p>
        </div>
    </div>

    

    <!-- DIVIDER -->
    <div class="mt-5 h-px w-full bg-white/20"></div>

    <p class="mt-3 text-xs sm:text-sm text-blue-100 text-center sm:text-left">
        Silakan lengkapi data atlet dengan benar dan sesuai kategori pertandingan
    </p>
</div>

        <!-- TEXT -->

    

    <!-- ALERT -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl font-semibold">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kejuaraan.daftar.store', $kejuaraan->slug) }}">
        @csrf

        <!-- SEARCH SISWA -->
        <div class="mb-6 relative">
            <label class="font-semibold mb-2 block">Cari Nama Siswa</label>
            <input id="search" type="text" autocomplete="off"
                   placeholder="Ketik nama siswa..."
                   class="w-full border-2 border-blue-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-400">

            <input type="hidden" name="siswa_id" id="siswa_id">
            <div id="dropdown"
                 class="absolute w-full bg-white border rounded-xl shadow-lg mt-1 hidden max-h-60 overflow-y-auto z-50">
            </div>
        </div>

        <!-- DATA SISWA -->
        <div class="grid md:grid-cols-2 gap-4 mb-8">
            <input id="nama" name="nama_lengkap" placeholder="Nama Lengkap"
                   class="border rounded-xl p-3 bg-gray-50">
            
                   <div id="info-kuota"
     class="mb-6 hidden text-xs text-gray-600 bg-yellow-50 border border-yellow-200 rounded-xl p-3">
    <b>Kuota:</b>
    <span id="kuota-text">-</span>
</div>

            <input id="tempat" name="tempat_lahir" placeholder="Tempat Lahir"
                   class="border rounded-xl p-3 bg-gray-50">

            <input id="tanggal" type="date" name="tanggal_lahir"
                   class="border rounded-xl p-3 bg-gray-50">

            <select id="jk" name="jenis_kelamin"
                    class="border rounded-xl p-3 bg-gray-50" placeholder="Jenis Kelamin">
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="L">👦 Laki-laki</option>
                <option value="P">👧 Perempuan</option>
            </select>

            <input id="unit" placeholder="Unit Latihan"
                   class="border rounded-xl p-3 bg-gray-50">

            <input type="hidden" name="units_id" id="unit_id">
            <input type="hidden" name="sabuk" id="sabuk">
        </div>

        <div class="mb-6">
    <label class="font-semibold block mb-2">Penggunaan Kuota</label>

    <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio"
                   name="use_kuota"
                   value="1"
                   checked
                   class="accent-blue-600">
            <span class="text-sm">Gunakan Kuota</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio"
                   name="use_kuota"
                   value="0"
                   class="accent-gray-600">
            <span class="text-sm">Tidak Gunakan Kuota</span>
        </label>
    </div>

    <p class="text-xs text-gray-500 mt-1">
        Kuota hanya berkurang jika opsi <b>Gunakan Kuota</b> dipilih
    </p>
</div>

        <!-- KATEGORI -->
        <div class="mb-6">
            <label class="font-semibold">Kategori Pertandingan</label>
            <select id="kategori" name="kategori_pertandingan"
                    class="w-full border-2 border-blue-200 rounded-xl p-3 mt-2" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="kyorugi">Kyorugi</option>
                <option value="poomsae">Poomsae</option>
            </select>
        </div>


        <!-- KYORUGI -->
        <div id="kyorugi" class="hidden bg-blue-50 p-5 rounded-2xl mb-6">
            <h3 class="font-bold text-blue-700 mb-4">🥋 Data Kyorugi</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <input type="number" step="0.1" name="berat_badan"
                       placeholder="Berat Badan (kg)"
                       disabled
                       class="border rounded-xl p-3">

                <input type="number" step="0.1" name="tinggi_badan"
                       placeholder="Tinggi Badan (cm)"
                       disabled
                       class="border rounded-xl p-3">
            </div>
        </div>

        <!-- POOMSAE -->
        <div id="poomsae" class="hidden bg-green-50 p-5 rounded-2xl mb-6">
            <h3 class="font-bold text-green-700 mb-4">🧘‍♂️ Data Poomsae</h3>

            <input id="sabuk_text" readonly
                   placeholder="Sabuk otomatis dari database"
                   disabled
                   class="w-full border rounded-xl p-3 bg-gray-100 mb-4">
        </div>


        <button class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-bold text-lg">
            Daftarkan Peserta
        </button>
    </form>
</div>

<script>
/* DATA SISWA */
const SISWA = @json($siswaJson);

const search = document.getElementById('search');
const dropdown = document.getElementById('dropdown');

function pilihSiswa(s) {
    siswa_id.value = s.id;
    nama.value = s.nama;
    tempat.value = s.tempat;
    tanggal.value = s.tanggal;
    jk.value = s.jk;
    unit.value = s.unit;
    unit_id.value = s.unit_id;
    sabuk.value = s.sabuk;

  const infoKuota = document.getElementById('info-kuota');
    const kuotaText = document.getElementById('kuota-text');

    if (s.kuota !== null && s.kuota !== undefined) {
        infoKuota.classList.remove('hidden');
        kuotaText.innerHTML = `
            Sisa kuota kelas: 
            <b class="text-yellow-700">${s.kuota}</b>
        `;
    } else {
        infoKuota.classList.remove('hidden');
        kuotaText.innerHTML = `
            Kuota kelas: <b class="text-gray-500">Tidak tersedia</b>
        `;
    }

  
    search.value = s.nama;
    dropdown.classList.add('hidden');
}

search.addEventListener('input', function () {
    const key = this.value.toLowerCase();
    dropdown.innerHTML = '';
    if (key.length < 2) return dropdown.classList.add('hidden');

    const hasil = SISWA.filter(s => s.nama.toLowerCase().includes(key)).slice(0, 6);
    if (!hasil.length) return dropdown.classList.add('hidden');

    hasil.forEach(s => {
        const div = document.createElement('div');
        div.className = 'px-4 py-3 cursor-pointer hover:bg-blue-100';
        div.innerHTML = `<b>${s.nama}</b><div class="text-sm text-gray-500">${s.unit}</div>`;
        div.onclick = () => pilihSiswa(s);
        dropdown.appendChild(div);
    });

    dropdown.classList.remove('hidden');
});

/* KATEGORI LOGIC */
const kategori = document.getElementById('kategori');
const kyorugi = document.getElementById('kyorugi');
const poomsae = document.getElementById('poomsae');
const sabukText = document.getElementById('sabuk_text');

kategori.addEventListener('change', () => {
    kyorugi.classList.add('hidden');
    poomsae.classList.add('hidden');

    kyorugi.querySelectorAll('input').forEach(i => i.disabled = true);
    poomsae.querySelectorAll('input, select').forEach(i => i.disabled = true);

    if (kategori.value === 'kyorugi') {
        kyorugi.classList.remove('hidden');
        kyorugi.querySelectorAll('input').forEach(i => i.disabled = false);
    }

    if (kategori.value === 'poomsae') {
        poomsae.classList.remove('hidden');
        poomsae.querySelectorAll('input, select').forEach(i => i.disabled = false);
        sabukText.value = sabuk.value || '-';
    }
});
</script>

</body>
</html>
