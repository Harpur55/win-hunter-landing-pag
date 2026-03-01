<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.mySwiper', {
        loop: true,
        spaceBetween: 20,
        slidesPerView: 1,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        // navigation: {
        //   nextEl: '.swiper-button-next',
        //   prevEl: '.swiper-button-prev',
        // },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 5,
            },
        },
    });
</script>


<script>
    function toggleDescription(index) {
        const el = document.getElementById(`desc-${index}`);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            el.classList.add('animate-fade-in');
        } else {
            el.classList.add('hidden');
            el.classList.remove('animate-fade-in');
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.coach-swiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: true,
            grabCursor: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.swiper').forEach(function(el) {

            new Swiper(el, {
                slidesPerView: 1,
                spaceBetween: 12,
                loop: false,
                autoHeight: true,


                speed: 800, // makin besar makin smooth
                resistanceRatio: 0.85, // efek tarik lebih natural
                touchRatio: 1.2, // responsif saat swipe
                longSwipesRatio: 0.2,
                grabCursor: true,
                watchSlidesProgress: true,

                pagination: {
                    el: el.querySelector('.swiper-pagination'),
                    clickable: true,
                },

                navigation: {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev'),
                },

                on: {
                    init: function() {
                        getActiveGalleryId(this);
                    },
                    slideChange: function() {
                        getActiveGalleryId(this);
                    }
                }
            });

        });


        /* ===============================
           GET ACTIVE GALLERY ID
        =============================== */
        function getActiveGalleryId(swiperInstance) {
            if (!swiperInstance?.slides?.length) return null;

            const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
            if (!activeSlide) return null;

            const activeGalleryId = activeSlide.dataset.id;

            console.log('Galeri aktif ID:', activeGalleryId);
            window.activeGalleryId = activeGalleryId;

            return activeGalleryId;
        }


        /* ===============================
           GALLERY MODAL
        =============================== */
        window.openGalleryModal = function(src, title) {
            const modal = document.getElementById('galleryModal');
            const image = document.getElementById('galleryModalImage');
            const titleEl = document.getElementById('galleryModalTitle');

            if (!modal || !image || !titleEl) return;

            image.src = src;
            titleEl.innerText = title;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closeGalleryModal = function() {
            const modal = document.getElementById('galleryModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        const galleryModal = document.getElementById('galleryModal');
        galleryModal?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGalleryModal();
            }
        });

    });
</script>




<script>
    /* ===============================
       MOBILE MENU
    =============================== */
    const btnOpen = document.getElementById('menu-toggle');
    const btnClose = document.getElementById('menu-close');
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-overlay');
    const links = menu?.querySelectorAll('a');

    function openMenu() {
        menu?.classList.remove('translate-x-full');
        overlay?.classList.remove('pointer-events-none');
        overlay?.classList.add('opacity-100');
    }

    function closeMenu() {
        menu?.classList.add('translate-x-full');
        overlay?.classList.add('pointer-events-none');
        overlay?.classList.remove('opacity-100');
    }

    btnOpen?.addEventListener('click', openMenu);
    btnClose?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);
    links?.forEach(link => link.addEventListener('click', closeMenu));


    /* ===============================
       DOCUMENT MODAL
    =============================== */
    window.openDocModal = function(btn) {
        const modal = document.getElementById('docModal');
        const frame = document.getElementById('docFrame');

        if (!modal || !frame) return;

        frame.src = btn.dataset.doc;
        modal.classList.remove('hidden');

        document.addEventListener('contextmenu', blockContextMenu);
        document.addEventListener('keydown', blockKeys);
    };

    window.closeDocModal = function() {
        const modal = document.getElementById('docModal');
        const frame = document.getElementById('docFrame');

        if (!modal || !frame) return;

        frame.src = '';
        modal.classList.add('hidden');

        document.removeEventListener('contextmenu', blockContextMenu);
        document.removeEventListener('keydown', blockKeys);
    };

    function blockContextMenu(e) {
        e.preventDefault();
    }

    function blockKeys(e) {
        if (
            e.key === 'PrintScreen' ||
            (e.ctrlKey && ['p', 's', 'u'].includes(e.key.toLowerCase()))
        ) {
            e.preventDefault();
            alert('Screenshot & download dinonaktifkan');
        }
    }
</script>

{{-- loading screen --}}

<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("loading-screen");

        loader.classList.add("opacity-0", "transition-opacity", "duration-700");

        setTimeout(() => {
            loader.style.display = "none";
        }, 700);
    });
</script>
