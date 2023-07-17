const mix = require('laravel-mix');

mix.js('src/main.js', 'js')
    .vue()
    .version()
    .sass('src/sass/main.scss', 'css')
    .setPublicPath('public');