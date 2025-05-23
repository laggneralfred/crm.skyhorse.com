/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'media', // Use system theme
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
