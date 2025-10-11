/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./vendor/filament/**/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'sans-serif'],
            },
            backgroundImage: {
            }
        },
    },
    plugins: [],
}