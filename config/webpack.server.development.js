const PRODUCTION = false
const SERVER = true

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/ignoreScss.js')(config)
config = require('./webpack/provideDomOnServer.js')(config)

config.output.filename = 'assets/js/devServer.js'

module.exports = config
