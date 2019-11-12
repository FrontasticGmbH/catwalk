const PRODUCTION = false
const SERVER = false

let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/browserDevTools.js')(config)
config = require('./webpack/namedModules.js')(config)
config = require('./webpack/developmenPerformance.js')(config)
config = require('./webpack/loadCss.js')(config)

config.output.filename = 'webpack/js/bundle.js'

module.exports = config
