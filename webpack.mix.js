let mix = require('laravel-mix');
require('dotenv').config();
let child_process = require('child_process');

let cmd = process.env.MIX_AFTER_CMD;

mix.js('src/resources/assets/js/app.js', 'src/public/js')
    .sass('src/resources/assets/sass/app.scss', 'src/public/css/')
    .sourceMaps()
    .then(() => {
        if(cmd != null) {
            child_process.exec(cmd, function (error, stdout, stderr) {
                if (error) throw error;

                console.log(stdout, stderr);
            });
        }
    });
