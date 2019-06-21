const path = require('path')

module.exports = function (modules)
{
    var pathSeparator = path.sep
    if (pathSeparator == '\\') {
        pathSeparator = '\\\\'
    }

    var moduleRegExps = modules.map((moduleName) => {
        return new RegExp('node_modules' + pathSeparator + moduleName + pathSeparator + '(?!node_modules)')
    })

    return function (modulePath) {
        if (/node_modules/.test(modulePath)) {
            for (var i = 0; i < moduleRegExps.length; i ++) {
                if (moduleRegExps[i].test(modulePath)) {
                    return false
                }
            }

            return true
        }

        return false
    }
}
