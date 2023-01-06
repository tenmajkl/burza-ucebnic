const mix = require('laravel-mix')
require('laravel-mix-svelte');

mix
    .ts('resources/ts/main.ts', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ])
    .svelte()
