let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/fronts/js');

mix.sass('resources/assets/sass/app.scss', 'public/fronts/css');

mix.sass('resources/assets/sass/app_mobile.scss', 'public/fronts_mobile/css');


mix.sass('resources/assets/sass/bijoux-mobile.scss', 'public/fronts_mobile/css');
mix.sass('resources/assets/sass/loungewear-mobile.scss', 'public/fronts_mobile/css');

mix.sass('resources/assets/sass/desktop-homeware.scss', 'public/fronts/css');
mix.sass('resources/assets/sass/desktop-bijoux.scss', 'public/fronts/css');

mix.config.fileLoaderDirs.fonts = 'public/fronts/fonts';
