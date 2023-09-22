const mix = require('laravel-mix');

mix.js('src/index.js', 'js')
    .vue()
    .sass('src/sass/main.scss', 'css', {
        processUrls:false,
    })
    .version()
    //.version(['public/js/index.js', 'public/fonts','public/css/main.scss'])
    .copy('src/fonts', 'public/fonts')
    .copy('src/favicon.ico', 'public/favicon.ico')
    .copy('src/index.html', 'public')
    //.options({processCssUrls: false,})
    .setPublicPath('public');
