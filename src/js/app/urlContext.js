import _ from 'lodash'

/**
 * Provides unified handling of URL context.
 *
 * URL parameters that do not impact execution of a server action *MUST* be prefixed with an '_' (underscore). This
 * class allows working with action and non-action specific parameters.
 */
class UrlContext {
    static parameterKeyFilter = /^(s|nocrawl|_.*|nodeId)$/

    static getActionHash = (route) => {
        return route.route + '-' + JSON.stringify(UrlContext.getActionParameters(route.parameters))
    }

    static getActionParameters = (parameters) => {
        return _.pickBy(
            parameters,
            /**
             * @param {*} value
             * @param {string} key
             * @returns {boolean}
             */
            (value, key) => {
                return (key.match(UrlContext.parameterKeyFilter) === null)
            }
        )
    }

    static hasChanged = (lastRoute, route) => {
        if (!lastRoute) {
            return true
        }

        if (lastRoute.route !== route.route) {
            return true
        }

        return UrlContext.getActionHash(lastRoute) !==
            UrlContext.getActionHash(route)
    }
}

export default UrlContext
