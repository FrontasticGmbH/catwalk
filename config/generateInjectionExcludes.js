const path = require('path')
const paths = require('./paths')
const fs = require('fs')
const chalk = require('chalk')
const os = require('os')


function removeCommentedCode (code) {
    // NOTE: very edge case bug, removes previous character in inline comments. Only a problem if a customer writes
    // ComponentInjector.set('componentName',/*{whatever}*/ Comonent)
    return code.replace(/\/\*[\s\S]*?\*\/|([^\\:]|^)\/\/.*$/gm, "")
}

function getCustomerOverrides () {
    var data = fs.readFileSync(path.join(paths.appSrc, 'js/injection.js'), 'utf8')
    if (data) {
        data = removeCommentedCode(data)
        // match "ComponentInjector.set({string}{any nummber of spaces},", map out string, map removing quotes
        return [...data.matchAll(/(ComponentInjector.set\(('|`|")[A-Za-z0-9]+('|`|")\s*,)/g)]
            .map(res => res[0].match(/'[A-Za-z0-9]+'/)[0])
            .map(res => res.substring(1, res.length - 1))
    }
    return []
}

/**
 * @typedef {Object} InjectableComponent
 * @property {string} componentString - The string representing the injection component, the first argument of the ComponentInjector.set/return func
 * @property {string} location - The absolute path of the component file
 */

/**
 * @param {string} filePath - The absolute path to the file to search for exported ComponentInjector returns
 */
function getInjectorReturnExports (filePath) {
    var data = fs.readFileSync(filePath, 'utf8')
    if (data) {
        data = removeCommentedCode(data)
        // match all exports of "ComponentInjector.return({string}," with any negligable spaces, map out string
        // then map to object with sting contexts and filepath
        return [...data.matchAll(/(export \s*(default)?\s*ComponentInjector.return\(('|`|")[A-Za-z0-9]+('|`|")\s*,)/g)]
            .map(res => res[0].match(/'[A-Za-z0-9]+'/)[0])
            .map(res => ({
                componentString: res.substring(1, res.length - 1),
                location: filePath
            })
            )
    }
    return []
}

/**
 * @param {string} dirPath - The absolute path to the parent directory to start searching
 * @param {Array<InjectableComponent>} replacableComponents - The replacable components passed recursively to return on top level
 */
function getInjectionReplacableComponents (dirPath, replacableComponents) {
    replacableComponents = replacableComponents || []
    let currentReplacableComponents = []
    fs.readdirSync(dirPath).forEach(currentPath => {
        currentPath = path.join(dirPath, currentPath)
        var stats = fs.lstatSync(currentPath)
        if (stats.isDirectory()) {
            currentReplacableComponents = getInjectionReplacableComponents(currentPath, currentReplacableComponents)
        }
        else {
            if (stats.isFile()) {
                var splitPath = currentPath.split('.')
                if (splitPath.length > 1) {
                    var ext = splitPath[splitPath.length - 1]
                    if (ext === "tsx" || ext === "jsx" || ext === "ts" || ext === "js") {
                        currentReplacableComponents = currentReplacableComponents.concat(getInjectorReturnExports(currentPath))
                    }
                }
            }
        }
    })
    return currentReplacableComponents.concat(replacableComponents)
}

/**
 * @param {string} replacedComponentString - The string representing the component overriden in their injection.js file
 * @param {string} injectorWebpackAlias - The string representing the alias to the ComponentInjector file
 */
function generateReplacedComponentFileContents (replacedComponentString, injectorWebpackAlias) {
    const isDevelopment = process.env.NODE_ENV === 'development' || process.env.BABEL_ENV === 'development'
    const alert = `${replacedComponentString} replacement not found. If you've removed this override in your injector.js file`
        + " since bringing up your sandbox please restart the supervisor processes with \"sudo supervisorctl restart {customer}-{project}:\""
        + ", and restart the CLI if running webpack locally."

    return `import ComponentInjector from '${injectorWebpackAlias}'
export default ComponentInjector.return(${replacedComponentString}, () => ${isDevelopment ? `window.alert(${alert})` : null})`
}

/**
 * @param {string} replacedComponentString - The string representing the component overriden in their injection.js file
 * @param {string} injectorWebpackAlias - The string representing the alias to the ComponentInjector file
 * @param {string} replacementComponentPathName - The absolute path to create the replacement component
 */
function handleSubstituteComponent (replacedComponentString, injectorWebpackAlias, replacementComponentPathName) {
    const fileContents = generateReplacedComponentFileContents(replacedComponentString, injectorWebpackAlias)
    fs.writeFileSync(replacementComponentPathName, fileContents)
}

/**
 * @param {string} componentLocation - The absolute path to the replacable component
 * @param {string} replacementComponentLocation - The absolute path to the replacements component
 */
function createSymlink (componentLocation, replacementComponentLocation) {
    try {
        // Windows directory symlinks should be created as junctions because dir-type symlinks require administrator privileges. 
        // Junction symlinks need absolute path, which will be automatically created from the given relative path. But this is fine as we are not inside a container or VM.
        fs.symlinkSync(componentLocation, replacementComponentLocation, os.platform() === 'win32' ? 'junction' : undefined)
    }
    catch (e) {
        console.error(' ⚠️  Wasn\'t able to create symlink for ' + chalk.bold(componentLocation) + ': ' + e)
        return
    }
}

/**
 *
 * @param {Array<InjectableComponent>} injectionReplacableComponents - All components available to be overriden by the customer, their name and file location
 * @param {Array<InjectableComponent>} componentsToExclude - All components the customer has set to be overriden in their injection.js file
 * @param {string} injectorWebpackAlias - The string representing the alias to the ComponentInjector file
 */
function handleComponentSymlinks (injectionReplacableComponents, componentsToExclude, injectorWebpackAlias) {
    const replacementComponentDirPath = path.join(paths.catwalk, "src/js/replacedComponents/")
    if (!fs.existsSync(replacementComponentDirPath)) {
        fs.mkdirSync(replacementComponentDirPath)
    }
    injectionReplacableComponents.forEach(replacableComponent => {
        var exclIndex = componentsToExclude.findIndex(excl => excl.componentString === replacableComponent.componentString)
        let replacementComponentPathName = path.join(replacementComponentDirPath, `${replacableComponent.componentString.replace(/[^a-zA-Z]/g, "")}.js`)
        let lstat = fs.lstatSync(replacableComponent.location)
        if (exclIndex > -1) { // needs symlink
            handleSubstituteComponent(componentsToExclude[exclIndex].componentString, injectorWebpackAlias, replacementComponentPathName)
            if (lstat.isSymbolicLink()) { // if symlink exists
                if (fs.readlinkSync(replacableComponent.location) !== replacementComponentPathName) {// if symlink is not to correct path
                    fs.unlinkSync(replacementComponentPathName, replacementComponentPathName)
                }
                createSymlink(replacableComponent.location)
            }
            else { // symlink doesn't exist
                createSymlink(replacementComponentPathName, replacementComponentPathName)
            }
            componentsToExclude.splice(exclIndex, 1)
        }
        else { // doesn't need symlink
            if (lstat.isSymbolicLink()) { // if symlink exists
                fs.unlinkSync(replacementComponentPathName)
            }
        }
    })
}

/**
 *
 * @param {string} injectorWebpackAlias - The string representing the alias to the ComponentInjector file
 */
function generateInjectionExcludes (injectorWebpackAlias) {
    var customerOverrides = getCustomerOverrides()
    if (customerOverrides.length > 0) {
        var injectionReplacableComponents = getInjectionReplacableComponents(path.join(paths.catwalk, 'src/js'))
            .concat(getInjectionReplacableComponents(path.join(paths.theme, 'src/js')))

        var componentsToExclude = []

        customerOverrides.forEach(customerOverride => {
            componentsToExclude = componentsToExclude.concat(injectionReplacableComponents
                .filter(val => val.componentString === customerOverride))
        })

        handleComponentSymlinks(injectionReplacableComponents, componentsToExclude, injectorWebpackAlias)

        return componentsToExclude.map(excl => {
            var pathFromFrontastic = excl.location.split("frontastic")[excl.location.split("frontastic").length - 1].substring(1)
            var normalisedRegexSlashes = pathFromFrontastic.split(path.sep).join('(\\\\|\\/)')
            return new RegExp(normalisedRegexSlashes.split('.')[0])
        })
    }
    return []
}

module.exports = { generateInjectionExcludes }
