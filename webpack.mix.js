const mix = require("laravel-mix");
const path = require("path");

mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css");

mix.webpackConfig({
    resolve: {
        extensions: [".ts", ".tsx", ".js", ".jsx", ".vue", "*"],
        alias: {
            "@": path.resolve(__dirname, "resources/js/")
        }
    }
    // module: {
    //     rules: [
    //         {
    //             test: /\.scss$/,
    //             use: ["vue-style-loader", "css-loader", "sass-loader"]
    //         }
    //     ]
    // }
});
