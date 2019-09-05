

function printLog (diffLog) {
    if (diffLog.length === 0) {
        return
    }

    console.groupCollapsed(
        '%c📣 %c%s (%s)',
        'color: gray',
        'color: orange',
        'Hydration differences detected',
        diffLog.length
    )

    diffLog.forEach((diff) => {
        console.group(
            '%c%s: %c%s',
            'color: orange',
            diff.action,
            'color: gray',
            shortenPath(diff.path)
        )
        console.log(diff.payload)
        console.groupEnd()
    })

    console.groupEnd()
}

function shortenPath (path) {
    const pathMatches = path.match(/div#[a-z0-9-]+\.([^ ]*tastic[^ ].*)$/)
    return (pathMatches ? 'div.' + pathMatches[1] : path)
}

export default printLog
