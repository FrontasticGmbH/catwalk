const merge = require('webpack-merge')
const PrebuildPlugin = require('prebuild-webpack-plugin')
const paths = require('../paths')
const fs = require('fs')
const os = require('os')
const rimraf = require('rimraf')
const chalk = require('chalk')
const path = require('path')

const links = {
    'common': paths.repositoryRoot + '/paas/libraries/common',
    'catwalk': paths.repositoryRoot + '/paas/catwalk',
    'theme-boost': paths.repositoryRoot + '/paas/themes/frontastic/boost',
}

const fileExists = (path) => {
    try {
        if (fs.existsSync(path)) {
            return true
        }

        // Seems unreachable. Seriuously?!?
        return false
    } catch (e) {
        return false
    }
}

const ensureLinks = () => {
    for (let package in links) {
        let packageLocation = paths.appNodeModules + '/@frontastic/' + package
        let packageSource = links[package]

        if (!fileExists(packageSource)) {
            // This repository does not have the link targets, so we are
            // fine with normal packages.
            console.info(chalk.bold('@frontastic/' + package) + ' installed as common package, since we have no PAAS modifications.')
            continue
        }

        if (fileExists(packageLocation) && fs.statSync(packageLocation).isSymbolicLink()) {
            // All fine, this is the expected state
            continue
        }

        if (os.platform() === 'win32') {
            console.warn(' ⚠️  Changes in ' + chalk.bold('@frontastic/' + package) + ' won\'t be part of the build. Links required, but not supported on windows.')
            continue
        }

        if (fileExists(packageLocation)) {
            // Remove the copied package (from yarn install) to be able to
            // create the links
            rimraf.sync(packageLocation)
        }

        try {
            // Create symlink with relative path to make sure it works inside
            // and outside of the container / virtual machine.
            fs.symlinkSync(
                path.relative(paths.appNodeModules + '/@frontastic/', packageSource),
                packageLocation
            )
        } catch (e) {
            console.error(' ⚠️  Wasn\'t able to create symlink for ' + chalk.bold('@frontastic/' + package) + ': ' + e)
            continue
        }
    }
}

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        config,
        {
            plugins: [
                new PrebuildPlugin({
                    build: ensureLinks,
                    watch: ensureLinks,
                }),
            ],
        },
    )
}
