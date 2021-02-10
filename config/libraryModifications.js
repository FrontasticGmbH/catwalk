const paths = require('./paths')
const glob = require('glob')
const path = require('path')
const fs = require('fs')

module.exports = (config, PRODUCTION, SERVER) => {
    const extensions = [].concat(
        glob.sync(paths.appSrc + '/../node_modules/*/*/.webpack.module.js'),
        glob.sync(paths.appSrc + '/../../node_modules/*/*/.webpack.module.js')
    )

    const packageJson = JSON.parse(fs.readFileSync(paths.appPackageJson))
    const packages = Object.keys(packageJson.dependencies || {}).concat(Object.keys(packageJson.devDependencies || {}))

    for (let extension of extensions) {
        let extensionFunction = require(extension)
        let packageName = path.basename(path.dirname(extension))
        let organizationName = path.basename(path.dirname(path.dirname(extension)))

        if (packages.includes(organizationName + "/" + packageName)) {
            console.log("* Applying webpack extensions from " + organizationName + " / " + packageName)
            config = extensionFunction(config, PRODUCTION, SERVER)
        }
    }

    return config
}
