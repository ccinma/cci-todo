const mix = require("laravel-mix");

const js_path = "resources/js";
const sass_path = "resources/sass";
const public_js_path = "public/assets/js"
const public_sass_path = "public/assets/sass"

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

mix.js(`${js_path}/app.js`, public_js_path)
    .sass(`${sass_path}/app.scss`, public_sass_path)
    .sass(`${sass_path}/themes/_dark.scss`, public_sass_path)
    .version();