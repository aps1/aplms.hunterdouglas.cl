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

mix.styles([
    'resources/css/bootstrap.css',
    'resources/css/font-awesome.css',
    'resources/css/animate.css',
    'resources/css/plugins/chosen/bootstrap-chosen.css',
    'resources/css/plugins/datatables/datatables.min.css',
    'resources/css/plugins/toastr/toastr.min.css',
    'resources/css/plugins/daterangepicker/daterangepicker-bs3.css',
    'resources/css/plugins/select2/select2.min.css',
    'resources/css/style.css',
    'resources/css/custom.css',
], 'public/css/all.css');

mix.scripts([
    'resources/js/jquery-3.1.1.min.js',
    'resources/js/popper.min.js',
    'resources/js/bootstrap.min.js',
    'resources/js/plugins/metisMenu/jquery.metisMenu.js',
    'resources/js/plugins/slimscroll/jquery.slimscroll.min.js',
    'resources/js/plugins/chosen/chosen.jquery.js',
    // 'resources/js/plugins/datatables/datatables.min.js',
    // 'resources/js/plugins/datatables/datatables.bootstrap4.min.js',
    'resources/js/plugins/toastr/toastr.min.js',
    'resources/js/plugins/ChartJs/Chart.min.js',
    'resources/js/plugins/daterangepicker/moment.js',
    'resources/js/plugins/daterangepicker/daterangepicker.js',
    'resources/js/plugins/select2/select2.full.min.js',
    'resources/js/inspinia.js',
    'resources/js/plugins/pace/pace.min.js',
], 'public/js/all.js');