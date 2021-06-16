const paths = require('./paths')
const glob = require('glob')
const path = require('path')
const fs = require('fs')

module.exports = (config, PRODUCTION, SERVER, SINGLE_SERVER = false) => {
    const extensions = [].concat(
        glob.sync(SINGLE_SERVER
            ? path.join(paths.repositoryRoot, '/*/node_modules/*/*/.webpack.module.js')
            : path.join(paths.appSrc, '/../node_modules/*/*/.webpack.module.js')),
        glob.sync(path.join(paths.appSrc, '/../../node_modules/*/*/.webpack.module.js'))
    )

    const packageJson = SINGLE_SERVER
        ? [paths.sharedProjectRoot, ...paths.projectRootPaths]
            .reverse()
            .reduce((accPackageJson, currentProjectPath) => {
                var currentPackageJson = JSON.parse(fs.readFileSync(path.join(currentProjectPath, 'package.json')))
                if (!currentProjectPath) {
                    return accPackageJson
                }
                return Object.assign(accPackageJson, currentPackageJson)
            }, {})
        : JSON.parse(fs.readFileSync(paths.appPackageJson))

    const packages = Object.keys(packageJson.dependencies || {}).concat(Object.keys(packageJson.devDependencies || {}))

    let appliedPackages = []
    extensions.forEach(extension => {
        let packageName = path.basename(path.dirname(extension))
        let organizationName = path.basename(path.dirname(path.dirname(extension)))
        let package = `${organizationName}/${packageName}`

        if (packages.includes(package)) {
            if (appliedPackages.indexOf(package) !== -1) {
                console.log("* Skipping webpack extensions from duplicate package " + package)
            }
            else {
                console.log("* Applying webpack extensions from " + package)
                config = require(extension)(config, PRODUCTION, SERVER)
                appliedPackages.push(package)
            }
        }
    })

    if (appliedPackages.length > 0) {
        console.log(`* Applied ${appliedPackages.length} webpack extension${appliedPackages.length > 1 ? "s" : ""}`)
    }

    return config
}
