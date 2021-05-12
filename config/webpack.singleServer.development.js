const paths = require('./paths')
const libraryModifications = require('./libraryModifications')
const { isModuleNotFoundError } = require('./webpack/helpers')

const PRODUCTION = false
const SERVER = true
const SINGLE_SERVER = true

let config = require('./webpack.js')(PRODUCTION, SERVER, SINGLE_SERVER)

config = require('./webpack/ignoreScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/provideDomOnServer.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)

config.output.filename = 'assets/js/devSingleServer.js'

let customConfigPath = paths.appSrc + '/../config/webpack.singleServer.development.js'
try {
    let projectWebpack = require(customConfigPath)
    config = projectWebpack(config, PRODUCTION, SERVER)
} catch (e) {
    if (!isModuleNotFoundError(customConfigPath, e.message)) {
        throw e
    }
    console.info('No build specific project webpack extension found in config/webpack.server.development.js – skip: ' + e.message)
}

config = libraryModifications(config, PRODUCTION, SERVER, SINGLE_SERVER)

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
