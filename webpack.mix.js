// webpack.mix.js

let mix = require('laravel-mix');
let webpack = require('webpack');
require('laravel-mix-polyfill');

mix.setPublicPath("src/Resources/public");

// Collection field
mix.js('assets/js/form-type-collection.js', '');
mix.js('assets/js/form-type-collection-sortable.js', '');
// Association field
mix.css('assets/css/form-type-association.css', '');
mix.js('assets/js/form-type-association-list.js', '');
mix.js('assets/js/form-type-association-new-ajax.js', '');

mix.polyfill();

mix.webpackConfig({
    output: {
        publicPath: 'bundles/easyfields/',
    },
    plugins: [
        // fix ReferenceError: Buffer/process is not defined
        new webpack.ProvidePlugin({
            process : 'process/browser',
            Buffer  : ['buffer', 'Buffer']
        })
    ]
})
