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




//unit function

const wrapper = document.getElementById('unit-slider-wrapper');
let isDown = false;
let startX;
let scrollLeft;
let autoScrollInterval;

// Manual drag scroll
wrapper.addEventListener('mousedown', (e) => {
  isDown = true;
  startX = e.pageX - wrapper.offsetLeft;
  scrollLeft = wrapper.scrollLeft;
});
wrapper.addEventListener('mouseleave', () => isDown = false);
wrapper.addEventListener('mouseup', () => isDown = false);
wrapper.addEventListener('mousemove', (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - wrapper.offsetLeft;
  const walk = (x - startX);
  wrapper.scrollLeft = scrollLeft - walk;
});

// Touch scroll
wrapper.addEventListener('touchstart', (e) => {
  isDown = true;
  startX = e.touches[0].clientX;
  scrollLeft = wrapper.scrollLeft;
});
wrapper.addEventListener('touchend', () => isDown = false);
wrapper.addEventListener('touchmove', (e) => {
  if (!isDown) return;
  const x = e.touches[0].clientX;
  const walk = x - startX;
  wrapper.scrollLeft = scrollLeft - walk;
});

// Auto scroll logic
function startAutoScroll() {
  autoScrollInterval = setInterval(() => {
    wrapper.scrollLeft += 1;
    // Reset ke awal untuk loop
    if (wrapper.scrollLeft >= wrapper.scrollWidth / 2) {
      wrapper.scrollLeft = 0;
    }
  }, 20); // kecepatan scroll
}

function stopAutoScroll() {
  clearInterval(autoScrollInterval);
}

wrapper.addEventListener('mouseenter', stopAutoScroll);
wrapper.addEventListener('mouseleave', startAutoScroll);

// Start on load
startAutoScroll();



