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

mix.webpackConfig({
    resolve: {

    },
    node: {
        fs: 'empty',
    }
});

mix.js('resources/assets/js/pa.js', 'public/js')
    .sass('resources/assets/sass/pa.scss', 'public/css').version();

mix.js('resources/assets/js/organizer.js', 'public/js').version();