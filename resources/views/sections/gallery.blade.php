<section id="galery"
    class="relative py-16 px-4 sm:px-6 lg:px-20 
           bg-gradient-to-br from-emerald-50 via-white to-blue-50">

    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-14">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl 
                       font-extrabold tracking-tight text-gray-900">
                Galeri Kegiatan
            </h2>

            <p class="mt-4 text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
                Dokumentasi momen terbaik dan penuh inspirasi dari berbagai kegiatan kami.
            </p>
        </div>

        {{-- Swiper --}}
        <div class="swiper galerySwiper">

            <div class="swiper-wrapper">

                @foreach ($galleries as $gallery)
                    <div class="swiper-slide pb-10">

                        <div class="group relative bg-gradient-to-b from-blue-600/40 to-transparent backdrop-blur-xl
                                    border border-white/40
                                    shadow-xl shadow-gray-100/40
                                    rounded-3xl p-6 sm:p-10
                                    transition duration-500 hover:shadow-2xl">

                            {{-- Judul --}}
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 text-center  mb-4 mt-4">
                                {{ $gallery->title }}
                            </h3>

                            {{-- Deskripsi --}}
                            @if ($gallery->description)
                                <p class="mt-4 text-gray-600 text-center 
                                          max-w-3xl mx-auto leading-relaxed backdrop-blur-sm bg-white/60 rounded-lg p-4 text-2xl sm:text-xl rounded-lg border border-gray-200
                                          font-medium text-gray-700 transition duration-300 hover:bg-white/80 hover:border-gray-300
                                          ">
                                    {{ $gallery->description }}
                                </p>
                            @endif

                            {{-- Divider Modern --}}
                            <div class="mt-6 mb-8 flex justify-center">
                                <span class="w-20 h-1 rounded-full 
                                             bg-gradient-to-r from-emerald-400 to-blue-500"></span>
                            </div>

                            {{-- Grid Foto Responsive --}}
                           <div class="grid grid-cols-3 gap-4">

    @foreach ($gallery->images_url as $image)
        <button type="button"
            onclick="openGalleryModal('{{ $image }}', '{{ addslashes($gallery->title) }}')"
            class="relative group overflow-hidden rounded-2xl 
                   focus:outline-none focus:ring-2 
                   focus:ring-emerald-400">

            <picture>
                <source srcset="{{ $image }}" type="image/webp">

                <img src="{{ $image }}"
                    alt="{{ $gallery->title }}"
                    loading="lazy"
                    class="w-full aspect-square object-cover
                           transition duration-500 ease-out
                           group-hover:scale-105">
            </picture>

            {{-- Overlay --}}
            <div
                class="absolute inset-0 bg-gradient-to-t 
                       from-black/60 via-black/20 to-transparent
                       opacity-0 group-hover:opacity-100
                       transition duration-300 flex 
                       items-end justify-center p-4">

                <span class="text-white text-sm font-medium">
                    Lihat Foto
                </span>
            </div>

        </button>
    @endforeach

</div>
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Pagination modern --}}
            <div class="swiper-pagination mt-6"></div>

            {{-- Navigation (hidden mobile) --}}
            <div class="hidden sm:block">
                <div class="swiper-button-next text-gray-700"></div>
                <div class="swiper-button-prev text-gray-700"></div>
            </div>

        </div>
    </div>
</section>