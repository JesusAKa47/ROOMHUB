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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                /* Paleta RoomHub — café: Primario #6F4E37, Secundario #A67C52, Acento #52796F, Fondo #FAF6F0 */
                'roomhub-primary': '#6F4E37',
                'roomhub-primary-dark': '#4A3728',
                'roomhub-secondary': '#A67C52',
                'roomhub-accent': '#52796F',
                'roomhub-bg': '#FAF6F0',
                'roomhub-card': '#FFFFFF',
                'roomhub-text': '#1C1917',
                'roomhub-text-muted': '#6B5344',
                'roomhub-border': '#E4E4ED',
                /* Compatibilidad */
                'roomhub-teal': '#6F4E37',
                'roomhub-teal-dark': '#4A3728',
                'roomhub-red': '#DC2626',
                'roomhub-red-dark': '#B91C1C',
            },
            backgroundImage: {
                'roomhub-gradient': 'linear-gradient(135deg, #6F4E37 0%, #A67C52 50%, #52796F 100%)',
                'roomhub-gradient-soft': 'linear-gradient(180deg, #F5EDE4 0%, #FAF6F0 100%)',
            },
        },
    },

    plugins: [forms],
};
