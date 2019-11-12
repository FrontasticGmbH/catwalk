const PRODUCTION = true
const SERVER = true

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/ignoreScss.js')(config)
config = require('./webpack/provideDomOnServer.js')(config)
config = require('./webpack/singleChunk.js')(config)

config.optimization = { minimize: true }
config.output.filename = 'assets/js/server.js'

module.exports = config
