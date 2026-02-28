  <section id="jadwal" class="bg-gradient-to-br from-emerald-50 to-blue-500 py-10 px-4 sm:px-6 lg:px-20">
        <div class="container mx-auto">
            <h2 class="text-3xl text-black text-center font-extrabold sm:text-4xl mb-4">Jadwal Latihan Pusat Dojang
                Waterland Metland Cileungsi</h2>
            <p class="text-md sm:text-xl text-gray-700 text-center mb-10"> Latihan rutin diadakan setiap hari dari
                Senin sampai Minggu. </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"> @php $jadwal = [ ['hari' => 'Senin', 'kelas' => [['Kelas Prestasi', '16:00 - 17:45WIB']]], ['hari' => 'Selasa', 'kelas' => [['Kelas Prestasi', '16:00 - 17:45WIB']]], ['hari' => 'Rabu', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 17:45WIB' ]]], ['hari'=> 'Kamis', 'kelas' => [['Kelas Reguler > 12 tahun', '16:00 - 17:45WIB']]], ['hari' => 'Jumat', 'kelas' => [['Kelas Reguler < 12 tahun', '16:00 - 17:45WIB' ]]], ['hari'=> 'Sabtu', 'kelas' => [ ['Kelas Reguler Semua Sabuk', '08:00 - 10:45 WIB'], ['Kelas Poomsae', '10:00 - 12:00 WIB'] ] ], ['hari' => 'Minggu', 'kelas' => [ ['Kelas Reguler Semua Sabuk', '07:30 - 09:45 WIB'], ['Kelas Poomsae', '10:00 - 12:00 WIB'] ] ] ]; @endphp @foreach ($jadwal as $j)
                    <div
                        class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 min-h-[200px] flex flex-col justify-between">
                        <div>
                            <h3 class="text-3xl font-bold text-blue-700 mb-4">{{ $j['hari'] }}</h3>
                            @foreach ($j['kelas'] as $kelas)
                                <div class="mb-4">
                                    <p class="text-gray-800 font-semibold text-3xl ">{{ $kelas[0] }}</p>
                                    <p class="text-gray-600 text-2xl">{{ $kelas[1] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>