const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const merge = require('webpack-merge')

const compileScss = require('./compileScss.js')

module.exports = (config) => {
    config = compileScss(config)

    // for (let rule of config.module.rules) {
    //     if (!rule.test) {
    //         continue
    //     }

    //     let tests = Array.isArray(rule.test) ? rule.test : [rule.test]
    //     for (let regexp of tests) {
    //         if (regexp.test('test.css') || regexp.test('test.scss')) {
    //             rule.use.unshift({ loader: MiniCssExtractPlugin.loader })
    //             break
    //         }
    //     }
    // }

    // return merge.smart(
    //     config,
    //     {
    //         plugins: [
    //             // Extract CSS code statically
    //             new MiniCssExtractPlugin({
    //                 // Options similar to the same options in
    //                 // webpackOptions.output both options are optional
    //                 filename: 'assets/css/[name].[hash:8].css',
    //                 chunkFilename: 'assets/css/[name].[hash:8].css',
    //             }),
    //         ],
    //     },
    // )
    return config;
}
