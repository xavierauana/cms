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

mix.js('src/resources/js/app.js', 'src/public/js/cms')
   .extract(['vue', 'jquery', 'jquery-ui', 'axios', 'lodash', 'summernote'], 'src/public/js/cms/vendor.js')
   .sass('src/resources/sass/app.scss', 'src/public/css/cms')
   .webpackConfig({
                    resolve: {
                      alias: {
                        'jquery-ui': 'jquery-ui-dist/jquery-ui.js'
                      }
                    },
                  })

