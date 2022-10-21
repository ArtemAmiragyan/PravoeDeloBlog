const mix = require('laravel-mix');
const aliasWebpackConfig = require('./webpack.config');

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

mix.ts('resources/js/app.ts', 'build/app')
  .vue({ version: 3 })
  .sass('resources/sass/app.scss', 'build/app')
  .webpackConfig(aliasWebpackConfig)
  .sourceMaps()
  .version();
