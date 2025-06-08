/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#4F46E5', // Indigo 600
        secondary: '#F59E0B', // Amber 500
        accent: '#10B981', // Emerald 500
        neutral: '#374151', // Gray 700
        'base-100': '#FFFFFF', // White
      },
      fontFamily: {
        sans: ['"Barlow Condensed"', 'sans-serif'],
        serif: ['Merriweather', 'serif'],
          //barlow: ['"Barlow Condensed"', 'sans-serif'],
      },
    },
  },

  plugins: [],
}