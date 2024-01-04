const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/rappasoft/laravel-livewire-tables/resources/views/tailwind/**/*.blade.php',
		'./vendor/filament/**/*.blade.php',
		'./app/**/*.php',
		'./resources/**/*.html',
		'./resources/**/*.js',
		'./resources/**/*.php'
	],
	theme: {
		extend: {
			fontFamily: {
				sans: ['Inter', ...defaultTheme.fontFamily.sans]
			},
			colors: {
				primary: {
					50: '#fef2e6',
					100: '#fce5cc',
					200: '#fbd8b3',
					300: '#f9cb99',
					400: '#f8be80',
					500: '#f6b166',
					600: '#f5a44d',
					700: '#f39733',
					800: '#f28a1a',
					900: '#6b7324'
				},
				secondary: {
					50: '#eef6ec',
					100: '#dceed8',
					200: '#cbe5c5',
					300: '#b9ddb1',
					400: '#a8d49e',
					500: '#96cb8a',
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
			}
		}
	},
	daisyui: {
		styled: true,
		rtl: false,
		logs: false,
		themes: [
			{
				default: {
					primary: '#35567f',
					'primary-focus': '#f07d00',
					'primary-content': '#ffffff',
					secondary: '#62b250',
					'secondary-focus': '#50a93c',
					'secondary-content': '#ffffff',
					accent: '#35567f',
					'accent-focus': '#1e4371',
					'accent-content': '#ffffff',
					neutral: '#bbbbbb',
					'neutral-focus': '#b1b3b7',
					'neutral-content': colors.gray[50],
					'base-100': '#ffffff',
					'base-200': '#f9fafb',
					'base-300': '#d1d5db',
					'base-content': '#1f2937',
					info: '#2094f3',
					success: '#009485',
					warning: '#ff9900',
					error: '#ff5724'
				}
			}
		]
	},
	plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography'), require('daisyui')]
};
