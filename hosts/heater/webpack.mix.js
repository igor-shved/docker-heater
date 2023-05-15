const mix = require('laravel-mix');
//const webpackConfig = require('./webpack.config');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app/heater.js', 'public/js').vue()
    .js('resources/js/app/menu.js', 'public/js').vue()
    .js('resources/js/app/debugSite.js', 'public/js').vue()
    .version()
    //.webpackConfig(webpackConfig)
    .sass('resources/sass/main.scss', 'public/css');
// if (mix.inProduction()) {
//     mix.version();
// } else {
//     mix.sourceMaps();
// }
