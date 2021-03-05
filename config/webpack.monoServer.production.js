
const fs = require('fs')
const path = require('path')
const paths = require('./paths')

const PRODUCTION = true
const SERVER = true
const repositoryRoot = paths.repositoryRoot

const deepClone = function (obj) {
    if (obj === null || typeof (obj) !== 'object' || 'isActiveClone' in obj)
        return obj;
    if (Array.isArray(obj))
        return obj.map(prop => deepClone(prop))

    if (obj instanceof Date)
        var temp = new obj.constructor(); //or new Date(obj);
    else
        var temp = obj.constructor();

    for (var key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            obj['isActiveClone'] = null
            temp[key] = deepClone(obj[key])
            delete obj['isActiveClone']
        }
    }
    return temp
}

const projectRootPaths = fs.readdirSync(repositoryRoot)
    .map(pathName => path.join(repositoryRoot, pathName))
    .filter(pathName => fs.lstatSync(pathName).isDirectory())
    .filter(dir => fs.existsSync(path.join(dir, 'config/project.yml')))


let config = require('./webpack.js')(PRODUCTION, SERVER)

config = require('./webpack/ignoreScss.js')(config, PRODUCTION, SERVER)
config = require('./webpack/provideDomOnServer.js')(config, PRODUCTION, SERVER)
config = require('./webpack/singleChunk.js')(config, PRODUCTION, SERVER)
config = require('./webpack/linkDependencies.js')(config, PRODUCTION, SERVER)

var projectSpecificConfigs = projectRootPaths.map(projectRoot => {
    let projectSpecificConfig = deepClone(config)
    let customConfigPath = path.join(projectRoot, 'config/webpack.server.production.js')
    let hasModifications = false
    try {
        let projectWebpack = require(customConfigPath)
        projectSpecificConfig = projectWebpack(projectSpecificConfig, PRODUCTION, SERVER)
        hasModifications = true
    } catch (e) {
        if (!isModuleNotFoundError(customConfigPath, e.message)) {
            throw e
        }
        console.info(`No build specific project webpack extension found in ${customConfigPath} – skip: ` + e.message)
    }

    projectSpecificConfig = libraryModifications(projectSpecificConfig, PRODUCTION, SERVER, path.join(projectRoot, 'src'))

    customConfigPath = path.join(projectRoot, 'config/webpack.post.js')
    try {
        let webpackPostProcessing = require(customConfigPath)
        projectSpecificConfig = webpackPostProcessing(projectSpecificConfig, PRODUCTION, SERVER)
        hasModifications = true
    } catch (e) {
        if (!isModuleNotFoundError(customConfigPath, e.message)) {
            throw e
        }
        console.info(`No build specific project webpack extension found in ${customConfigPath} – skip: ` + e.message)
    }
    return {
        customConfigPath,
        projectSpecificConfig,
        hasModifications
    }
})

if (projectSpecificConfigs.filter(mod => mod.hasModifications).length === 0) {
    // TODO: handle library modifications for all projects
}
else {
    projectSpecificConfigs.forEach(projectSpecificConfig => {
        if (projectSpecificConfig.hasModifications) {
            //TODO: set project specific config to projectPath
        }
        else {
            // TODO: handle library modifications (node_modules)
        }
    })
}
//TODO: check node_modules for duplications in all instances above and warn/error for conflicting versions

config.optimization = { minimize: true }
config.output.filename = 'assets/js/monoServer.js'
