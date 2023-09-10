const mix = require('laravel-mix');

mix.js('src/main.js', 'js')
    .version()
    .vue()
    .copy('src/fonts', 'public/fonts')
    .copy('src/favicon.ico', 'public/favicon.ico')
    .copy('src/index.html', 'public')
    .sass('src/sass/main.scss', 'css', {
        processUrls:false,
    })
    //.options({processCssUrls: false,})
    .setPublicPath('public/');