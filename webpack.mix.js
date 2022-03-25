const mix = require("laravel-mix")

const js_path = "resources/js"
const sass_path = "resources/sass"
const image_path = "resources/images"

const public_js_path = "public/assets/js"
const public_sass_path = "public/assets/css"
const public_image_path = "public/assets/images"


mix.webpackConfig({
    // stats: {
    //     children: true
    // }
});


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

mix.js(`${js_path}/app.js`, public_js_path).vue()
    .sass(`${sass_path}/app.scss`, public_sass_path)
    .sass(`${sass_path}/themes/_dark.scss`, public_sass_path)
    .copy(`${image_path}`, public_image_path)
    .version();