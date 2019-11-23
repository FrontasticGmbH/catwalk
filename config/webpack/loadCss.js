const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const merge = require('webpack-merge')

const compileScss = require('./compileScss.js')

module.exports = (config) => {
    config = compileScss(config)
    //
    //    for (let rule of config.module.rules) {
    //        if (!rule.test) {
    //            continue
    //        }
    //
    //        let tests = Array.isArray(rule.test) ? rule.test : [rule.test]
    //        for (let regexp of tests) {
    //            if (regexp.test('test.css') || regexp.test('test.scss')) {
    //                rule.use.unshift({ loader: require.resolve('style-loader') })
    //                break
    //            }
    //        }
    //    }
    //
    return config
}
