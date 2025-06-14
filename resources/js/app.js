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










// Ini cukup agar langsung aktif saat halaman dimuat
