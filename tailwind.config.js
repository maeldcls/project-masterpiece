/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        'kanit': ['Kanit', 'sans-serif'],
      },
    },
  },
  plugins: [],
}

