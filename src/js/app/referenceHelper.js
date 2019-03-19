/**
 * @returns boolean
 */
export const isReferenceAbsoluteHttpLink = (reference) => {
    if (reference.type !== 'link') {
        return false
    }

    const target = reference.target
    return target.startsWith('http://') || target.startsWith('https://')
}

/**
 * @returns string
 */
export const getPathForAppAndReference = (router, reference) => {
    if (isReferenceAbsoluteHttpLink(reference)) {
        throw new Error(`Reference to ${reference.target} is an absolute link, which is not supported by getPathForAppAndReference`)
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
    if (isReferenceAbsoluteHttpLink(reference)) {
        window.location.href = reference.target
        return
    }

    router.history.push(getPathForAppAndReference(router, reference))
}
