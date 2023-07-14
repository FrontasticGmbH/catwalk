const paths = require('./paths')
const libraryModifications = require('./libraryModifications')
const { isModuleNotFoundError } = require('./webpack/helpers')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const TerserPlugin = require("terser-webpack-plugin")

const PRODUCTION = true
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/namedModules.js')(config, PRODUCTION, SERVER)
config = require('./webpack/buildStatistics.js')(config, PRODUCTION, SERVER)
config = require('./webpack/manifest.js')(config, PRODUCTION, SERVER)
config = require('./webpack/serviceWorker.js')(config, PRODUCTION, SERVER)
config = require('./webpack/compileScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)
require('./webpack/overwriteInjectionReplacedComponents')(PRODUCTION)

config.optimization = {
    minimize: true,
    minimizer: [new CssMinimizerPlugin(), new TerserPlugin({
        test: /\.js(\?.*)?$/i,
        extractComments: false,
    })],
    splitChunks: {
        chunks: 'all',
        minSize: 10 * 1024,
        minChunks: 1,
        automaticNameDelimiter: '~',
        cacheGroups: {
            icons: {
                minChunks: 1,
                test: /\/layout\/icons\//,
                priority: 10,
                name: 'icons~main',
                // Force the 'chunk.js' suffix which is no longer generated in webpack 5
                // but required for the build pipeline for now.
                filename: 'assets/js/icons~main.[contenthash:8].chunk.js',
            },
            defaultVendors: {
                test: /\/node_modules\//,
                priority: 5,
                name: 'vendors~main',
                // Force the 'chunk.js' suffix which is no longer generated in webpack 5
                // but required for the build pipeline for now.
                filename: 'assets/js/vendors~main.[contenthash:8].chunk.js',
            },
            default: {
                minChunks: 2,
                priority: -20,
                reuseExistingChunk: true,
            },
        },
    },
}

let customConfigPath = paths.appSrc + '/../config/webpack.browser.production.js'
try {
    let projectWebpack = require(customConfigPath)
    config = projectWebpack(config, PRODUCTION, SERVER)
} catch (e) {
    if (!isModuleNotFoundError(customConfigPath, e.message)) {
        throw e
    }
    console.info('No build specific project webpack extension found in config/webpack.browser.production.js – skip: ' + e.message)
}

config = libraryModifications(config, PRODUCTION, SERVER)

customConfigPath = paths.appSrc + '/../config/webpack.post.js'
try {
    let webpackPostProcessing = require(customConfigPath)
    config = webpackPostProcessing(config, PRODUCTION, SERVER)
} catch (e) {
    if (!isModuleNotFoundError(customConfigPath, e.message)) {
        throw e
    }
    console.info('No project webpack post processing extension found in config/webpack.post.js – skip: ' + e.message)
}

module.exports = config
