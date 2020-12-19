const mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/vue/app.js', 'public/js/components/app.js')
    .copy('node_modules/@fortawesome/fontawesome-free/svgs/*', 'public/fonts/vendor/fontawesome-free/')
    .copy('node_modules/@fortawesome/fontawesome-free/sprites/*', 'public/fonts/vendor/fontawesome-free/')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts/*', 'public/fonts/vendor/fontawesome-free/')
    .sass('resources/sass/app.scss', 'public/css')
    .purgeCss();
