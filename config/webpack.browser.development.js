const paths = require('./paths')

const PRODUCTION = false
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/browserDevTools.js')(config)
config = require('./webpack/namedModules.js')(config)
config = require('./webpack/developmenPerformance.js')(config)
config = require('./webpack/loadCss.js')(config)

config.output.filename = 'webpack/js/bundle.js'

try {
    let projectWebpack = require(paths.appSrc + '/../config/webpack.browser.development.js')
    config = projectWebpack(config)
} catch (e) {
    console.info('No build specific project webpack extension found in config/webpack.browser.development.js – skip.')
}

module.exports = config
