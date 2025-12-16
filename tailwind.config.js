import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: "#ea2f30",
                accent: "#b16130",
                'attendance-green': '#10b981',
                'attendance-blue': '#3b82f6',
                'attendance-red': '#ef4444',
            },
            fontFamily: {
                sans: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                inter: ["Inter", ...defaultTheme.fontFamily.sans],
                cambay: ["Cambay", ...defaultTheme.fontFamily.sans],
                quantico: ["Quantico", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
