import './bootstrap';
// import Swiper from 'swiper';
// import 'swiper/css';
// import 'swiper/css/navigation';
// import 'swiper/css/pagination';



// resources/js/app.js
function togleNavbar() {
  const toggleBtn = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const closeBtn = document.getElementById('menu-close');
  const menuLinks = document.querySelectorAll('#mobile-menu a');

  toggleBtn?.addEventListener('click', () => {
    mobileMenu?.classList.remove('translate-x-full');
    mobileMenu?.classList.add('translate-x-0');
  });

  closeBtn?.addEventListener('click', () => {
    mobileMenu?.classList.add('translate-x-full');
    mobileMenu?.classList.remove('translate-x-0');
  });

  menuLinks.forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu?.classList.add('translate-x-full');
      mobileMenu?.classList.remove('translate-x-0');
    });
  });
}

togleNavbar();



// const swiper = new Swiper('.mySwiper', {
//   loop: true,
//   spaceBetween: 20,
//   slidesPerView: 5,
//   pagination: {
//     el: '.swiper-pagination',
//     clickable: true,
//   },
//   navigation: {
//     nextEl: '.swiper-button-next',
//     prevEl: '.swiper-button-prev',
//   },
//   breakpoints: {
//     640: {
//       slidesPerView: 2,
//     },
//     768: {
//       slidesPerView: 3,
//     },
//     1024: {
//       slidesPerView: 5,
//     },
//   },
// });








// Ini cukup agar langsung aktif saat halaman dimuat
