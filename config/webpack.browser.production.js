const paths = require('./paths')

const PRODUCTION = true
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/namedModules.js')(config)
config = require('./webpack/buildStatistics.js')(config)
config = require('./webpack/manifest.js')(config)
config = require('./webpack/serviceWorker.js')(config)
config = require('./webpack/extractCss.js')(config)
config = require('./webpack/svgr.js')(config)

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
    config = projectWebpack(config)
} catch (e) {
    console.info('No build specific project webpack extension found in config/webpack.browser.production.js – skip.')
}

module.exports = config
