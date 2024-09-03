import preset from './vendor/filament/support/tailwind.config.preset';
import form from '@tailwindcss/forms'
import colors from 'tailwindcss/colors'

export default {
    darkMode: ['variant', [
        '@media (prefers-color-scheme: light) { &:not(.light *) }',
        '&:is(.light *)',
    ]],
    presets: [
        preset
    ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {

            colors : {
                'primary': '#DC2626',
                'secondary': '#EC4545',
                'tertiary': '#044571',
                'white-transparent' : 'rgba(255,255,255,0.75)',
            },
            backgroundImage: {
                "rs-image": "url('/resources/assets/images/rsumm-front.jpg')",
                "indonesia-map": "url('/resources/assets/images/indonesia-map.webp')",
                "circles": "url('/resources/assets/images/circles.svg')",
                "brand": "url('/resources/assets/images/logo-rs.webp')",
                "lines": "url('/resources/assets/images/lines.svg')",
                "rectangles": "url('/resources/assets/images/rectangles.png')",
            },
            aspectRatio: {
                'custom': '3.8 / 4',
            },
        },
    },
    plugins: [
        form
    ],
}

