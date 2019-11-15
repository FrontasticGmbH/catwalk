const paths = require('./paths')

const PRODUCTION = false
const SERVER = true

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/ignoreScss.js')(config)
config = require('./webpack/provideDomOnServer.js')(config)
config = require('./webpack/svgr.js')(config)

config.output.filename = 'assets/js/devServer.js'

try {
    let projectWebpack = require(paths.appSrc + '/../config/webpack.server.development.js')
    config = projectWebpack(config)
} catch (e) {
    console.info('No build specific project webpack extension found in config/webpack.server.development.js – skip.')
}

module.exports = config
