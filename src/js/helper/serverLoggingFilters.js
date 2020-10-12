/*
    Originally set up to remove unsightly and annyoing useLayoutEffect ssr console warnings, implemented in a way to make it easily extensible.
    Not to be used instead of actually fixing warnings, errors etc...
*/

/**
 * Returns a boolean indicating whether or not to exclude the payload from logging.
 */
export const filterPayload = payload => {
    switch (payload.severity) {
    case 'INFO':
    case 'DEBUG':
    case 'WARNING':
        return false
    case 'ERROR':
        return filterWarning(payload)
    default:
        return false
    }
}

/*
    Not to be used as a permanently but currently the best solution to remove the ssr warning spam in the console that's annoying
    both our developers and customers. Current real solution appears to be to override ReactCurrentDispatcher however this is not
    encouraged (source Dan Abramov, https://overreacted.io/how-does-setstate-know-what-to-do/) and can lead to far worse and more
    difficult to debug errors, especially in a project this complex.
    As it's a known issue within the react community it may be fixed in a future release.
*/
const filterWarning = payload => {
    return payload.message.indexOf('useLayoutEffect does nothing on the server, because its effect cannot ' +
        "be encoded into the server renderer's output format") > -1
}
