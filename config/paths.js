const path = require('path')
const fs = require('fs')
const url = require('url')

// Make sure any symlinks in the project folder are resolved:
// https://github.com/facebookincubator/create-react-app/issues/637
const appDirectory = fs.realpathSync(process.cwd())
const resolveApp = (relativePath) => {
    return path.resolve(appDirectory, relativePath)
}

const envPublicUrl = process.env.PUBLIC_URL

function ensureSlash (path, needsSlash) {
    const hasSlash = path.endsWith('/')
    if (hasSlash && !needsSlash) {
        return path.substr(path, path.length - 1)
    } else if (!hasSlash && needsSlash) {
        return `${path}/`
    } else {
        return path
    }
}

const getPublicUrl = (appPackageJson) => {
    return envPublicUrl || require(appPackageJson).homepage
}

// We use `PUBLIC_URL` environment variable or "homepage" field to infer
// "public path" at which the app is served.
// Webpack needs to know it to put the right <script> hrefs into HTML even in
// single-page apps that may serve index.html for nested URLs like /todos/42.
// We can't use a relative path in HTML because we don't want to load something
// like /todos/42/static/js/bundle.7289d.js. We have to know the root.
function getServedPath (appPackageJson) {
    const publicUrl = getPublicUrl(appPackageJson)
    const servedUrl = envPublicUrl || (publicUrl ? url.parse(publicUrl).pathname : '/')
    return ensureSlash(servedUrl, true)
}

function findDirectoryContainingFile(filename, directory) {
    while (true) {
        var list = fs.readdirSync(directory)

        if ((index = list.indexOf(filename)) !== -1) {
            return directory
        } else if (directory === '/') {
            throw new Error('File ' + filename + ' not found.')
        } else {
            directory = path.normalize(directory + '/../')
        }
    }
}

let repositoryRoot = findDirectoryContainingFile('.customer_provision.yml', resolveApp('src'))

module.exports = {
    dotenv: resolveApp('.env'),
    appBuild: resolveApp('build'),
    appPublic: resolveApp('public'),
    appIndexJs: resolveApp('src/js/index.js'),
    serverIndexJs: resolveApp('src/js/server.js'),
    appPackageJson: resolveApp('package.json'),
    appSrc: resolveApp('src'),
    yarnLockFile: resolveApp('yarn.lock'),
    testsSetup: resolveApp('test/js/setup.js'),
    appNodeModules: resolveApp('node_modules'),
    publicUrl: getPublicUrl(resolveApp('package.json')),
    servedPath: getServedPath(resolveApp('package.json')),
    commonSrc: resolveApp('../libraries'),
    repositoryRoot: repositoryRoot,
    theme: repositoryRoot + '/node_modules/@frontastic/theme-boost',
    themeSrc: repositoryRoot + '/node_modules/@frontastic/theme-boost/src',
}
