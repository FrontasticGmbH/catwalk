const paths = require('./paths')
const glob = require('glob')
const path = require('path')

module.exports = (config, PRODUCTION, SERVER) => {
    const extensions = glob.sync(paths.appSrc + '/../node_modules/*/*/.webpack.module.js')
    for (let extension of extensions) {
        let extensionFunction = require(extension)
        let packageName = path.basename(path.dirname(extension))
        let organizationName = path.basename(path.dirname(path.dirname(extension)))
        console.log("* Applying webpack extensions from " + organizationName + " / " + packageName)

        config = extensionFunction(config, PRODUCTION, SERVER)
    }

    return config
}
