const mix = require('laravel-mix');

mix.js('src/main.js', 'js')
    .js('src/js/include_mix_manifest.js', 'js')
    .version()
    .vue()
    .copy('src/css', 'public/css')
    .copy('src/fonts', 'public/fonts')
    .copy('src/images', 'public/images')
    .copy('src/index.html', 'public')
    .sass('src/sass/main.scss', 'css')
    .setPublicPath('public');