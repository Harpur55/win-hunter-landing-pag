<div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-6">
    {{-- HEADER KEJUARAAN --}}
    <div class="mb-6 border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $kejuaraan->nama_kejuaraan }}
        </h1>
        <p class="text-gray-600">
            📍 {{ $kejuaraan->lokasi }}
        </p>
        <p class="text-gray-600">
            📅 {{ \Carbon\Carbon::parse($kejuaraan->tanggal_mulai)->format('d M Y') }}
            - {{ \Carbon\Carbon::parse($kejuaraan->tanggal_selesai)->format('d M Y') }}
        </p>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('kejuaraan.daftar.store', $kejuaraan->slug) }}" class="space-y-5">
        @csrf

        {{-- Pilih Siswa --}}
        <div>
            <label class="block font-medium mb-1">Nama Lengkap</label>
            <select name="siswa_id" required class="w-full rounded-lg border-gray-300">
                <option value="">-- Pilih Siswa --</option>
                @foreach ($siswas as $siswa)
                    <option value="{{ $siswa->id }}">
                        {{ $siswa->nama_lengkap }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Kategori Pertandingan --}}
        <div>
            <label class="block font-medium mb-1">Kategori Pertandingan</label>
            <select name="kategori_pertandingan" required class="w-full rounded-lg border-gray-300">
                <option value="">-- Pilih --</option>
                <option value="kyorugi">Kyorugi</option>
                <option value="poomsae">Poomsae</option>
            </select>
        </div>

        {{-- Berat Badan --}}
        <div>
            <label class="block font-medium mb-1">Berat Badan (kg)</label>
            <input type="number" name="berat_badan" step="0.1"
                class="w-full rounded-lg border-gray-300"
                placeholder="Contoh: 45">
        </div>

        {{-- Tinggi Badan --}}
        <div>
            <label class="block font-medium mb-1">Tinggi Badan (cm)</label>
            <input type="number" name="tinggi_badan"
                class="w-full rounded-lg border-gray-300"
                placeholder="Contoh: 160">
        </div>

        {{-- Taegeuk --}}
        


        {{-- Tingkat Kategori --}}
        <div>
            <label class="block font-medium mb-1">Tingkat Kategori</label>
            <select name="tingkat_kategori" class="w-full rounded-lg border-gray-300">
                <option value="">-- Pilih --</option>
                <option value="Beginer">Beginer</option>
                <option value="Advance">Advance</option>
                <option value="Pro">Pro</option>
                <option value="Regular">Regular</option>
            </select>
        </div>

        {{-- Kelompok Usia --}}
        <div>
            <label class="block font-medium mb-1">Kelompok Usia</label>
            <select name="kategori_atlit" required class="w-full rounded-lg border-gray-300">
                <option value="pracadet">Pra-Cadet</option>
                <option value="cadet">Cadet</option>
                <option value="junior">Junior</option>
                <option value="senior">Senior</option>
            </select>
        </div>

        {{-- Medali --}}
        <div>
            <label class="block font-medium mb-1">Medali</label>
            <select name="medali" class="w-full rounded-lg border-gray-300">
                <option value="tidak_ada">Tidak Ada</option>
                <option value="emas">Emas</option>
                <option value="perak">Perak</option>
                <option value="perunggu">Perunggu</option>
            </select>
        </div>

        {{-- SUBMIT --}}
        <div class="pt-4">
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                Daftarkan Peserta
            </button>
        </div>
    </form>
</div>
