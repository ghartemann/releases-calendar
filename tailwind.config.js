const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        "./assets/**/*.js",
        "./assets/**/*.vue",
        "./templates/**/*.html.twig",
    ],
    prefix: "tw-",
    theme: {
        extend: {
            colors: {
                'accent': colors.rose[400]
            }
        },
    },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
    ],
}
