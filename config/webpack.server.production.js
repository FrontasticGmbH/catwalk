const paths = require('./paths')
const libraryModifications = require('./libraryModifications')
const { isModuleNotFoundError } = require('./webpack/helpers')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const TerserPlugin = require("terser-webpack-plugin")

const PRODUCTION = true
const SERVER = true

let config = require('./webpack.js')(PRODUCTION, SERVER)
config.devtool = 'cheap-module-source-map'

config = require('./webpack/ignoreScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/provideDomOnServer.js')(config, PRODUCTION, SERVER)
config = require('./webpack/singleChunk.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)
require('./webpack/overwriteInjectionReplacedComponents')(PRODUCTION, 'ComponentInjector')

config.optimization = {
    minimize: true,
    minimizer: [new CssMinimizerPlugin(), new TerserPlugin({
        test: /\.js(\?.*)?$/i,
    })],
    output: {
        filename: 'assets/js/server.js'
    }
}

let customConfigPath = paths.appSrc + '/../config/webpack.server.production.js'
try {
    let projectWebpack = require(customConfigPath)
    config = projectWebpack(config, PRODUCTION, SERVER)
} catch (e) {
    if (!isModuleNotFoundError(customConfigPath, e.message)) {
        throw e
    }
    console.info('No build specific project webpack extension found in config/webpack.server.production.js – skip: ' + e.message)
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
