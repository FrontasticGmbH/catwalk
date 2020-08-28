const paths = require('./paths')
const libraryModifications = require('./libraryModifications')

const PRODUCTION = true
const SERVER = true

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/ignoreScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/provideDomOnServer.js')(config, PRODUCTION, SERVER)
config = require('./webpack/singleChunk.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)

config.optimization = { minimize: true }
config.output.filename = 'assets/js/server.js'

try {
    let projectWebpack = require(paths.appSrc + '/../config/webpack.server.production.js')
    config = projectWebpack(config, PRODUCTION, SERVER)
} catch (e) {
    console.info('No build specific project webpack extension found in config/webpack.server.production.js – skip: ' + e.message)
}

config = libraryModifications(config, PRODUCTION, SERVER)

try {
    let webpackPostProcessing = require(paths.appSrc + '/../config/webpack.post.js')
    config = webpackPostProcessing(config, PRODUCTION, SERVER)
} catch (e) {
    console.info('No project webpack post processing extension found in config/webpack.post.js – skip: ' + e.message)
}

module.exports = config
