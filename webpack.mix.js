const mix = require('laravel-mix');

mix.ts("resources/js/app.ts", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.tsx?$/,
                    loader: "ts-loader",
                    options: { appendTsSuffixTo: [/\.vue$/] },
                    exclude: /node_modules/
                }
            ]
        },
        resolve: {
            extensions: ["*", ".js", ".jsx", ".vue", ".ts", ".tsx"]
        }
    });

mix.inProduction() ? mix.version() : mix.sourceMaps()