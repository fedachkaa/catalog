const mix = require('laravel-mix');

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

 mix.js('resources/js/general.js', 'public/js/general.js');
 mix.js('resources/js/universityAdminProfile.js', 'public/js/universityAdminProfile.js');
 
 mix.webpackConfig({
     output: {
         library: ['general', 'universityAdminProfile'],
         libraryTarget: 'umd',
         umdNamedDefine: true,
         globalObject: 'this'
     }
 });
 