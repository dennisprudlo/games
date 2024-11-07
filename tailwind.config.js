const svgToDataUri = require('mini-svg-data-uri');
const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');
const plugin = require('tailwindcss/plugin');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './app/**/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: colors.rose[500],
                    ...colors.rose,
                },
                success: {
                    DEFAULT: colors.green[800],
                    ...colors.green,
                },
                warning: {
                    DEFAULT: colors.yellow[700],
                    ...colors.yellow,
                },
                danger: {
                    DEFAULT: colors.rose[800],
                    ...colors.rose,
                },
                gray: colors.zinc,
                label: colors.zinc[500],
            },
            screens: {
                'xs': '500px',
                'below-sm': { 'max': '639px' },
            },
            fontFamily: {
                'sans': ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        plugin(function ({ addBase, theme }) {
            const selectLight   = svgToDataUri(`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="${ theme('colors.zinc.400') }" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8l4 4 4-4"/></svg>`);
            const selectDark    = svgToDataUri(`<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="${ theme('colors.zinc.500') }" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8l4 4 4-4"/></svg>`);

            const checkboxCheck = svgToDataUri(`<svg viewBox="0 0 16 16" fill="${ theme('colors.indigo.500') }" xmlns="http://www.w3.org/2000/svg"><path d="M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z"/></svg>`)
            const checkboxIndeterminate = svgToDataUri(`<svg viewBox="0 0 16 16" fill="${ theme('colors.indigo.500') }" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h8"/></svg>`);
            const radioCheck = svgToDataUri(`<svg viewBox="0 0 16 16" fill="${ theme('colors.indigo.500') }" xmlns="http://www.w3.org/2000/svg"><circle cx="8" cy="8" r="3"/></svg>`)

            addBase({
                'select': {
                    'background-image': `url("${ selectLight }")`
                },

                '.dark select': {
                    'background-image': `url("${ selectDark }")`
                },

                '[type=\'checkbox\']:not([checkbox-type="toggle"]):checked': {
                    'background-image': `url("${ checkboxCheck }")`
                },

                '[type=\'radio\']:checked': {
                    'background-image': `url("${ radioCheck }")`
                },

                '[type=\'checkbox\']:not([checkbox-type="toggle"]):indeterminate': {
                    'background-image': `url("${ checkboxIndeterminate }")`
                }
            })
        }),
    ],
}
