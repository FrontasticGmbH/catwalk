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

    static getActionParameters = (parameters = {}) => {
        let actionParameters = {}

        for (let [parameter, value] of Object.entries(parameters)) {
            if (parameter.match(UrlContext.parameterKeyFilter) === null) {
                actionParameters[parameter] = value
            }
        }

        return actionParameters
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
