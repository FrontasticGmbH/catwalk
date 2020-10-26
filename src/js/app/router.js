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
        let allParameters = {
            ...this.contextParameters(),
            ...parameters,
        }

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

        let unknownKeys = []
        for (let key of keys) {
            if (!allParameters.hasOwnProperty(key)) {
                unknownKeys.push(key)
            }
        }

        if (unknownKeys.length) {
            // eslint-disable-next-line no-console
            console.error('Missing values for ' + route + ': ' + unknownKeys.join(', '))
            return { pathname: '/', search: '' }
        }

        let query = []
        for (let [key, value] of Object.entries(allParameters)) {
            if (!keys.includes(key)) {
                query[key] = value
            }
        }

        let search = ''
        if (Object.keys(query).length > 0) {
            search = '?' + httpBuildQuery(query)
        }

        return {
            pathname: keys.reduce(
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

        return keys.reduce(
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

    this.getContext = function () {
        return this.context
    }

    this.setRoutes = function (routes) {
        this.routes = routes
    }

    this.contextParameters = function () {
        return this.context.toParameters()
    }

    this.getSimilarRoutes = function (route) {
        let distances = Object.keys(this.routes).map((key) => {
            return { route: key, distance: levenshtein.get(key, route) }
        })

        distances.sort((a, b) => {
            return a.distance - b.distance
        })

        return distances.slice(0, 5).map((value) => {
            return value.route
        })
    }
}

export default Router
