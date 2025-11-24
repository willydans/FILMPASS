/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
          indigo: {
              50: '#eff6ff',
              100: '#dbeafe',
              500: '#6366f1',
              600: '#4f46e5',
              700: '#4338ca',
          }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}