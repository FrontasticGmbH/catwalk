import _ from 'lodash'

/**
 * Provides unified handling of URL context.
 *
 * URL parameters that do not impact execution of a server action *MUST* be prefixed with an '_' (underscore). This
 * class allows working with action and non-action specific parameters.
 */
class UrlContext {
    static parameterKeyFilter = /^(s|nocrawl|_.*)$/

    static getActionHash = (parameters) => {
        return JSON.stringify(UrlContext.getActionParameters(parameters))
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

        return UrlContext.getActionHash(lastRoute.parameters) !== UrlContext.getActionHash(route.parameters)
    }
}

export default UrlContext
