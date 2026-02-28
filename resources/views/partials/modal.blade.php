  <div id="galleryModal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center transition">

        <div class="relative max-w-5xl w-full px-4">

            <button type="button" class="absolute -top-10 right-4 text-white text-2xl"
                onclick="closeGalleryModal()">
                ✕
            </button>

            <img id="galleryModalImage" src="" alt="Galeri"
                class="w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">

            <h4 id="galleryModalTitle" class="text-center text-white mt-4 text-lg font-semibold">
            </h4>
        </div>
    </div>



     <div id="docModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

        <div class="relative w-full h-full flex items-center justify-center p-4">
            <div class="relative bg-black rounded-xl w-full max-w-4xl h-[85vh] shadow-2xl overflow-hidden">

                <!-- CLOSE -->
                <button onclick="closeDocModal()"
                    class="absolute top-3 right-3 z-50 text-white bg-black/60 hover:bg-black px-3 py-1 rounded-lg text-sm">
                    ✕ Tutup
                </button>

                <!-- PREVIEW -->
                <iframe id="docFrame" class="w-full h-full" src="" frameborder="0"
                    sandbox="allow-same-origin">
                </iframe>

                <!-- WATERMARK -->
                <div
                    class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-10 text-white text-4xl font-bold rotate-[-30deg] select-none">
                    WIN HUNTER
                </div>

            </div>
        </div>
    </div>