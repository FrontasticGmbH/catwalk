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
