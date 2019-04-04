/**
 * @returns boolean
 */
export const isReferenceAbsoluteHttpOrMailtoLink = (reference) => {
    if (reference.type !== 'link') {
        return false
    }

    const target = reference.target
    if (!target) return false

    return target.startsWith('http://') || target.startsWith('https://') || target.startsWith('mailto:')
}

/**
 * @returns string
 */
export const getPathForAppAndReference = (router, reference) => {
    if (isReferenceAbsoluteHttpOrMailtoLink(reference)) {
        throw new Error(
            `Reference to ${reference.target} is an absolute link, which is not supported by getPathForAppAndReference`
        )
    }
    if (reference.type === 'link') {
        return reference.target
    }

    if (router.hasRoute(`node_${reference.target}`)) {
        return router.path(`node_${reference.target}`)
    }

    return '/__error'
}

/**
 * Navigate the user to the site corresponding to the given reference
 */
export const gotoReference = (router, reference) => {
    const isAbsolute = isReferenceAbsoluteHttpOrMailtoLink(reference)
    const openNewWindow = reference.mode === 'new_window'
    const target = isAbsolute ? reference.target : getPathForAppAndReference(router, reference)

    if (openNewWindow) {
        window.open(target)
    } else if (isAbsolute) {
        window.location.href = target
    } else {
        router.history.push(target)
    }
}
