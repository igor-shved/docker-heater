const mix = require('laravel-mix');

mix.js('src/main.js', 'js')
    .vue()
    .version()
    .copy('src/css', 'public/css')
    .copy('src/fonts', 'public/fonts')
    .sass('src/sass/main.scss', 'css')
    .setPublicPath('public');