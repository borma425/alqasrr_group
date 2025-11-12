/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './views/**/*.twig',
    './*.php',
    './inc/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#282828',
        secondary: '#BE755A',
        'secondary-orange': '#ff5a00',
        'text-gray': '#616161',
        'paragraph-grey': '#4A4949',
        'gray-light': '#BDBDBD',
      },
      fontFamily: {
        'aktiv': ['Aktiv Grotesk VF Trial', 'Helvetica', 'Arial', 'sans-serif'],
        'dubai': ['Dubai', 'Helvetica', 'Arial', 'sans-serif'],
      },
     
    },
  },
  plugins: [],
}

