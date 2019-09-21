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
    .js('resources/js/tinymce.min.js', 'public/js')
    .js('resources/js/1.12.1.jquery-ui.js', 'public/js')
    .js('resources/js/gcalendar-holidays.js', 'public/js')
    .js('resources/js/jquery-3.2.1.min.js', 'public/js')
    .sass('resources/sass/admin.scss', 'public/css');