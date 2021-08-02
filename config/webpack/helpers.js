function isModuleNotFoundError (path, errorMessage) {
    const expectedError = `cannot find module '${path.replace(/\\/g, "/")}'`.toLowerCase()
    const processedError = errorMessage.replace(/\\/g, "/").toLowerCase()
    return processedError.indexOf(expectedError) === 0
}

function generateHashCode (stringVal) {
    if (stringVal.length === 0) {
        return 0
    }
    var hash = 0, i, chr;
    for (i = 0; i < stringVal.length; i++) {
        chr = stringVal.charCodeAt(i)
        hash = ((hash << 5) - hash) + chr
        hash |= 0
    }
    return hash;
}

module.exports = {
    isModuleNotFoundError,
    generateHashCode
}
