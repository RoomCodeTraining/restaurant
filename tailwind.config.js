import preset from './vendor/filament/support/tailwind.config.preset'
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
        presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js",
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
          fontFamily: {
            sans: ['Roboto', ...defaultTheme.fontFamily.sans],
          },
          colors: {
            primary: {
              50: '#fef2e6',
              100: '#fce5cc',
              200: '#fbd8b3',
              300: '#f9cb99',
              400: '#f8be80',
              500: '#081D45',
              600: '#f5a44d',
              700: '#02aef0',
              800: '#f28a1a',
              900: '#f07d00'
            },
            secondary: {
              50: '#eef6ec',
              100: '#dceed8',
              200: '#cbe5c5',
              300: '#b9ddb1',
              400: '#a8d49e',
              500: '#0b5d78',
              600: '#85c377',
              700: '#73ba63',
              800: '#62b250',
              900: '#50a93c'
            },
            accent: {
              50: '#e9ecf1',
              100: '#d2d9e3',
              200: '#bcc7d4',
              300: '#a5b4c6',
              400: '#8fa1b8',
              500: '#788eaa',
              600: '#627b9c',
              700: '#4b698d',
              800: '#35567f',
              900: '#1e4371'
            },
            grey: {
              50: '#f7f7f8',
              100: '#eff0f0',
              200: '#e8e8e9',
              300: '#e0e1e1',
              400: '#d8d9da',
              500: '#d0d1d2',
              600: '#c8cacb',
              700: '#c1c2c3',
              800: '#b9bbbc',
              900: '#b1b3b4'
            },
            danger: colors.rose,
            success: colors.emerald,
            warning: colors.amber
          },
        },
        keyframes: {
          marquee: {
            '0%': { transform: 'translateX(100%)' },
            '100%': { transform: 'translateX(-100%)' },
          }
        },
        animation: {
          marquee: 'marquee 15s linear infinite',
        }
      },

      variants: {
        extend: {
          opacity: ['group-focus-within'], // Ajoutez une variante pour l'opacité lorsqu'en état "focus-within"
          visibility: ['group-focus-within'], // Ajoutez une variante pour la visibilité lorsqu'en état "focus-within"
        },
      },

    plugins: [
        forms,
        require('flowbite/plugin')
    ],
};
