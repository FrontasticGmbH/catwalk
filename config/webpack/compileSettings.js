const merge = require('webpack-merge')
const PrebuildPlugin = require('prebuild-webpack-plugin')
const paths = require('../paths')
const fs = require('fs')
const yaml = require('js-yaml')

const propertiesToHashmap = (object) => {
    let hashmap = {}

    const isObject = (value) => {
        return typeof value === 'object'
    }

    const addDelimiter = (a, b) => {
        return a ? a + b.charAt(0).toUpperCase() + b.slice(1) : b
    }

    const recursePaths = (object = {}, head = '') => {
        Object.keys(object).forEach((key) => {
            let value = object[key]
            let fullPath = addDelimiter(head, value.identifier || key)

            if (isObject(value)) {
                recursePaths(value, fullPath)
            } else if (value) {
                hashmap[fullPath] = value
            }
        })

        return hashmap
    }

    return recursePaths(object)
}

const updateSettings = (SINGLE_SERVER = false) => {
    let settings = {}

    try {
        let config = fs.readFileSync(paths.appSrc + '/../config/project.yml') // TODO: 
        let layout = yaml.safeLoad(config).data.layout || {}
        settings = propertiesToHashmap(layout)
    } catch (e) {
        console.error("Could not read theme configuration: " + e)
        return
    }

    fs.writeFileSync(
        paths.appSrc + '/scss/settings.scss', // TODO: 
        Object.entries(settings).reduce((head, [key, value]) => {
            return head + `\$${key}: '${value}';\n`
        }, `// This is an auto generated file, please edit config/project.yml instead\n\n`)
    )

    fs.writeFileSync(
        paths.appSrc + '/js/settings.js', // TODO: 
        Object.entries(settings).reduce((head, [key, value]) => {
            return head + `    ${key}: '${value}',\n`
        }, `// This is an auto generated file, please edit config/project.yml instead\n\nmodule.exports = {\n`) +
        `}\n`
    )
}

// Create file before anything else happens, especially before the tailind
// configuration is imported / processed by webpack
updateSettings() // TODO: 

module.exports = (config, PRODUCTION, SERVER, SINGLE_SERVER = false) => {
    return merge(
        config,
        {
            plugins: [
                new PrebuildPlugin({
                    build: () => updateSettings(SINGLE_SERVER),
                    watch: () => updateSettings(SINGLE_SERVER),
                }),
            ],
        },
    )
}
