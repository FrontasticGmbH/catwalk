function isModuleNotFoundError(path, errorMessage) {
    const expectedError = `cannot find module '${path.replace(/\\/g, "/")}'`.toLowerCase()
    const processedError = errorMessage.replace(/\\/g, "/").toLowerCase()
    return processedError.indexOf(expectedError) === 0
}

module.exports = {
    isModuleNotFoundError
}
