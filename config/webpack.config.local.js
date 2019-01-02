'use strict'

const paths = require('../config/paths')
const config = require('./webpack.config.dev.js')
const HtmlWebpackPlugin = require('html-webpack-plugin')

config.plugins.push(new HtmlWebpackPlugin({
    title: 'Frontastic Local Development',
    template: '../paas/catwalk/config/local.html',
}))

module.exports = config
