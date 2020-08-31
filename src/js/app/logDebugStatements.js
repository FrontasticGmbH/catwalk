export default (debugStatements, method = '', route = '') => {
    // eslint-disable-next-line no-console
    console.groupCollapsed(
        '%c💻 %c%s (%s: %s)',
        'color: gray',
        'color: lightcoral',
        'Server Debug',
        method,
        route
    )
    for (let debugLine of debugStatements) {
        // eslint-disable-next-line no-console
        console.log(...debugLine)
    }
    // eslint-disable-next-line no-console
    console.groupEnd()
}
