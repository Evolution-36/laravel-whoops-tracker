let mix = require('laravel-mix');

mix.js('src/resources/assets/js/app.js', 'src/public/js')
    .sass('src/resources/assets/sass/app.scss', 'src/public/css')
    .options({
        postCss: [
            require('autoprefixer')({
                grid: true,
                flex: true
            }),
            require("postcss-cssnext")({
                features: {
                    customProperties: false
                }
            })
        ]
    })
    .sourceMaps()
    .copyDirectory('src/resources/assets/images', 'src/public/images')
    .version();
