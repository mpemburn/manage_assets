const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "smoke-900": "rgba(0, 0, 0, 0.9)",
                "smoke-800": "rgba(0, 0, 0, 0.75)",
                "smoke-600": "rgba(0, 0, 0, 0.6)",
                smoke: "rgba(0, 0, 0, 0.5)",
                "smoke-400": "rgba(0, 0, 0, 0.4)",
                "smoke-200": "rgba(0, 0, 0, 0.25)",
                "smoke-100": "rgba(0, 0, 0, 0.1)",
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
