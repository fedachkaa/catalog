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
mix.js('resources/js/universityAdminProfile/teachers.js', 'public/js/universityAdminProfile/teachers.js');
mix.js('resources/js/universityAdminProfile/subjects.js', 'public/js/universityAdminProfile/subjects.js');
mix.js('resources/js/universityAdminProfile/students.js', 'public/js/universityAdminProfile/students.js');
mix.js('resources/js/universityAdminProfile/faculties.js', 'public/js/universityAdminProfile/faculties.js');
mix.js('resources/js/universityAdminProfile/university.js', 'public/js/universityAdminProfile/university.js');
mix.js('resources/js/universityAdminProfile/catalogs.js', 'public/js/universityAdminProfile/catalogs.js');
mix.js('resources/js/universityAdminProfile/edit-catalog.js', 'public/js/universityAdminProfile/edit-catalog.js');

mix.js('resources/js/teacher/subjects.js', 'public/js/teacher/subjects.js');
mix.js('resources/js/teacher/students.js', 'public/js/teacher/students.js');
mix.js('resources/js/teacher/catalogs.js', 'public/js/teacher/catalogs.js');

mix.webpackConfig({
     output: {
         library: ['general', 'universityAdminProfile', 'teacher'],
         libraryTarget: 'umd',
         umdNamedDefine: true,
         globalObject: 'this'
     }
 });
