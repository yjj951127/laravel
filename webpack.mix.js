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

// 得到package.json中的参数 --env.admin 转换成 一个对象 {admin: true}
const { env } = require('minimist')(process.argv.slice(2))

switch (env) {
    case 'admin':
        mix.js('resources/admin/js/app.js', 'public/dist/admin/js')
            .copy('resources/admin/less/app.css', 'public/dist/admin/css')
            .extract(['vue', 'view-design', 'vue-router', 'axios'])
            .setResourceRoot('/dist/admin/') // 设置资源目录
            .setPublicPath('public/dist/admin/') // 设置 mix-manifest.json 目录
            .webpackConfig({
                output: {
                    publicPath: '/dist/admin/', // 设置默认打包目录
                }
            })
            .browserSync({
                // 设置 开发时候的代理，需要提前执行 php artisan serve
                proxy: "http://127.0.0.1:8000",
            })
            .version();
        break;

    case 'home':
        mix.js('resources/home/js/app.js', 'public/dist/home/js')
            .copy('resources/home/less/app.css', 'public/dist/home/css')
            .extract(['vue', 'view-design', 'vue-router', 'axios'])
            .setResourceRoot('/dist/home/') // 设置资源目录
            .setPublicPath('public/dist/home/') // 设置 mix-manifest.json 目录
            .webpackConfig({
                output: {
                    publicPath: '/dist/home/', // 设置默认打包目录
                }
            })
            .browserSync({
                proxy: "http://127.0.0.1:8000",
            })
            .version();
        break;

    default:

        console.error('请选择编译的模块');

        return false;
}
