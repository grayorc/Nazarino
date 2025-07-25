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
                fadedPrimaryColor : "rgba(235,94,40,0.15)",
                primaryWhite : "#FFFCF2",
                postBg : "#cfccc8",
                SecondaryWhite : "#CCC5B9",
                PrimaryBlack : "#252422",
                SecondaryBlack : "#403D39",

                Background_color: "#1E1E2E",
                Sidebar_background: "#23232E",
                Sidebar_background_hover: "#373749",
                Secondary_accent_color: "#E91E63",
                Chart_background: "#2E2E3E",
            },
            keyframes: {
                expanding: {
                    "0%": { transform: "scaleX(0)", opacity: "0" },
                    "100%": { transform: "scaleX(1)", opacity: "100%" },
                },
                moving: {
                    "0%": { backgroundPosition: "0 0" },
                    "100%": { backgroundPosition: "-200% 0" },
                },
            },
            animation: {
                loading: "expanding 0.4s 0.7s forwards linear, moving 1s 1s infinite forwards linear",
            },
        },
    },

    plugins: [
        forms,
        require('tailwindcss-rtl'),
    ],
};
