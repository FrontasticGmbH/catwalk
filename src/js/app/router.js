import _ from 'lodash'
import { matchPath } from 'react-router'
import levenshtein from 'fast-levenshtein'

import { httpBuildQuery } from 'frontastic-common'

import Context from './context'

let Router = function (history, routes = {}, context = null) {
    this.history = history
    this.routes = routes
    this.context = context || new Context()

    this.parameterMatcher = /\{([A-Za-z0-9]+)\}/g

    this.location = function (route, parameters = {}) {
        let allParameters = _.extend({}, this.contextParameters(), parameters)

        if (!this.hasRoute(route)) {
            throw new Error(
                'Route ' + route + ' not defined, did you mean any of these: ' +
                this.getSimilarRoutes(route).join(', ')
            )
        }

        let keys = []
        let matches = false
        while ((matches = this.parameterMatcher.exec(this.routes[route].path))) { // eslint-disable-line no-cond-assign
            keys.push(matches[1])
        }

        let unknownKeys = _.difference(keys, _.keys(allParameters))
        if (unknownKeys.length) {
            throw new Error('Missing values for ' + route + ': ' + unknownKeys.join(', '))
        }

        let queryKeys = _.difference(_.keys(allParameters), keys)
        let search = ''
        if (queryKeys.length) {
            search = '?' + httpBuildQuery(_.pick(allParameters, queryKeys))
        }

        return {
            pathname: _.reduce(
                keys,
                function (link, key) {
                    return link.replace('{' + key + '}', allParameters[key])
                },
                this.routes[route].path
            ),
            search: search,
        }
    }

    this.hasRoute = function (route) {
        return !!(route in this.routes)
    }

    this.path = function (route, parameters = {}) {
        let location = this.location(route, parameters)
        return location.pathname + location.search
    }

    this.match = function (path) {
        for (let route in this.routes) {
            let matchResult = matchPath(
                path,
                {
                    path: this.reactRoute(route),
                    exact: true,
                }
            )

            if (matchResult) {
                return {
                    route: route,
                    parameters: matchResult.params,
                }
            }
        }

        throw new Error('No defined route match path: ' + path)
    }

    this.push = function (route, parameters = {}, state = null) {
        this.history.push(
            this.location(route, parameters), state
        )
    }

    this.replace = function (route, parameters = {}, state = null) {
        this.history.replace(
            this.location(route, parameters), state
        )
    }

    this.reactRoute = function (route) {
        if (!(route in this.routes)) {
            throw new Error(
                'Route ' + route + ' not defined, did you mean any of these: ' +
                this.getSimilarRoutes(route).join(', ')
            )
        }

        let keys = []
        let matches = false
        while ((matches = this.parameterMatcher.exec(this.routes[route].path))) { // eslint-disable-line no-cond-assign
            keys.push(matches[1])
        }

        const requirements = this.routes[route].requirements || {}

        return _.reduce(
            keys,
            function (link, key) {
                let keyRequirement = ''
                if (requirements[key]) {
                    keyRequirement = '(' + requirements[key] + ')'
                }
                return link.replace('{' + key + '}', ':' + key + keyRequirement)
            },
            this.routes[route].path
        )
    }

    this.setContext = function (context) {
        this.context = context
    }

    this.setRoutes = function (routes) {
        this.routes = routes
    }

    this.contextParameters = function () {
        return this.context.toParameters()
    }

    this.getSimilarRoutes = function (route) {
        return _.map(
            _.sortBy(
                _.mapValues(
                    this.routes,
                    function (value, key) {
                        return {
                            route: key,
                            distance: levenshtein.get(key, route),
                        }
                    }
                ),
                'distance'
            ).slice(0, 5),
            'route'
        )
    }
}

export default Router
