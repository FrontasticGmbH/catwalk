const paths = require('./paths')
const libraryModifications = require('./libraryModifications')

const PRODUCTION = true
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/namedModules.js')(config, PRODUCTION, SERVER)
config = require('./webpack/buildStatistics.js')(config, PRODUCTION, SERVER)
config = require('./webpack/manifest.js')(config, PRODUCTION, SERVER)
config = require('./webpack/serviceWorker.js')(config, PRODUCTION, SERVER)
config = require('./webpack/compileScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)

config.optimization = {
    minimize: true,
    splitChunks: {
        chunks: 'all',
        minSize: 10 * 1024,
        maxSize: 0,
        minChunks: 1,
        automaticNameDelimiter: '~',
        name: true,
        cacheGroups: {
            icons: {
                minChunks: 1,
                test: /\/layout\/icons\//,
                priority: 10,
            },
            vendors: {
                test: /\/node_modules\//,
                priority: 5,
            },
            default: {
                minChunks: 2,
                priority: -20,
                reuseExistingChunk: true,
            },
        },
    },
}

try {
    let projectWebpack = require(paths.appSrc + '/../config/webpack.browser.production.js')
    config = projectWebpack(config, PRODUCTION, SERVER)
} catch (e) {
    console.info('No build specific project webpack extension found in config/webpack.browser.production.js – skip: ' + e.message)
}

config = libraryModifications(config, PRODUCTION, SERVER)

try {
    let webpackPostProcessing = require(paths.appSrc + '/../config/webpack.post.js')
    config = webpackPostProcessing(config, PRODUCTION, SERVER)
} catch (e) {
    console.info('No project webpack post processing extension found in config/webpack.post.js – skip: ' + e.message)
}

module.exports = config
