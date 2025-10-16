/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // penting untuk sinkron dengan dark mode Filament

  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',

    // Wajib untuk semua komponen Filament (core dan panel)
    './vendor/filament/**/*.blade.php',
    './vendor/filament/**/*.js',

    // Tambahan agar plugin atau package Filament lain ikut ke-scan
    './vendor/awcodes/**/*.blade.php',
    './vendor/bezhanSalleh/**/*.blade.php',
    './vendor/filipfonseca/**/*.blade.php',
    './vendor/jeffgreco13/**/*.blade.php',
    './vendor/koala/**/*.blade.php',

    // Jika kamu buat custom panel di Filament 3 (misal: app/Filament/Admin/Resources)
    './app/Filament/**/*.php',
  ],

  theme: {
    extend: {
      colors: {
        primary: '#4F46E5',    // Indigo 600
        secondary: '#F59E0B',  // Amber 500
        accent: '#10B981',     // Emerald 500
        // neutral: '#374151',    // Gray 700
        'base-100': '#FFFFFF', // White
      },

      fontFamily: {
        sans: ['"Barlow Condensed"', 'sans-serif'],
        serif: ['Merriweather', 'serif'],
      },

      boxShadow: {
        soft: '0 2px 8px rgba(0, 0, 0, 0.05)',
        glow: '0 0 12px rgba(79, 70, 229, 0.4)',
      },

      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('tailwind-scrollbar-hide'),
  ],
};
