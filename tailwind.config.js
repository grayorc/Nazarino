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
            fontFamily: {
                sans: ['Vazirmatn','Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primaryColor : "#EB5E28",
                primaryWhite : "#FFFCF2",
                SecondaryWhite : "#CCC5B9",
                PrimaryBlack : "#252422",
                SecondaryBlack : "#403D39",
            },
        },
    },

    plugins: [
        forms,
        require('tailwindcss-rtl'),
    ],
};
