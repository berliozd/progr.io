import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';


/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                soft: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 1px 3px 0 rgb(0 0 0 / 0.06)',
                elevated: '0 4px 12px -2px rgb(0 0 0 / 0.08), 0 2px 6px -2px rgb(0 0 0 / 0.06)',
            },
        },
    },
    daisyui: {
        themes: [
            {
                light: {
                    'primary': '#4f46e5',
                    'primary-content': '#ffffff',
                    'secondary': '#7c3aed',
                    'secondary-content': '#ffffff',
                    'accent': '#0d9488',
                    'accent-content': '#ffffff',
                    'neutral': '#1e1b2e',
                    'neutral-content': '#e7e5f0',
                    'base-100': '#ffffff',
                    'base-200': '#f5f5f8',
                    'base-300': '#e9e8f0',
                    'base-content': '#1c1a27',
                    'info': '#0ea5e9',
                    'success': '#16a34a',
                    'warning': '#d97706',
                    'error': '#dc2626',
                    '--rounded-box': '1rem',
                    '--rounded-btn': '0.625rem',
                    '--rounded-badge': '1.9rem',
                },
            },
            {
                dark: {
                    'primary': '#818cf8',
                    'primary-content': '#1e1b4b',
                    'secondary': '#a78bfa',
                    'secondary-content': '#1e1b4b',
                    'accent': '#2dd4bf',
                    'accent-content': '#042f2e',
                    'neutral': '#181622',
                    'neutral-content': '#d6d3e0',
                    'base-100': '#151321',
                    'base-200': '#1c1929',
                    'base-300': '#242032',
                    'base-content': '#e7e5f0',
                    'info': '#38bdf8',
                    'success': '#4ade80',
                    'warning': '#fbbf24',
                    'error': '#f87171',
                    '--rounded-box': '1rem',
                    '--rounded-btn': '0.625rem',
                    '--rounded-badge': '1.9rem',
                },
            },
        ],
        darkTheme: 'dark',
        base: true,
        styled: true,
        logs: false,
    },

    plugins: [forms, daisyui],
};
