const mix = require('laravel-mix')
require('laravel-mix-svelte');

mix
    .js('resources/js/main.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ])
    .svelte()
