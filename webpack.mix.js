const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/assets/maundy/img/*.*', 'public/img')
    .copy('resources/assets/maundy/vendor/icofont/fonts/*.*', 'public/fonts')
    .styles([
        'resources/assets/maundy/vendor/icofont/icofont.min.css',
        'resources/assets/maundy/css/style.css',
    ], 'public/css/style.css', './');
