import logDebugStatements from './logDebugStatements'
import UrlContext from './urlContext'
import ComponentInjector from './injector'

let Api = function (router, store) {
    this.router = router
    this.store = store

    let intervals = []

    let stringifyBody = function (body) {
        if (body === null || body === undefined) {
            return null
        }

        if (typeof body === 'string' || body instanceof String) {
            return body
        }

        return JSON.stringify(body)
    }

    this.request = function (method, route, parameters = {}, body = null, success = null, error = null, signal = null) { // eslint-disable-line
        if (this.router.context.environment === 'local') {
            // Do not try to fetch any data when running in local environment
            return
        }

        parameters = parameters || {}
        return fetch(
            this.router.path(route, parameters),
            {
                method: method,
                headers: new Headers({
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }),
                body: stringifyBody(body),
                credentials: 'same-origin',
                cache: 'default',
                signal,
            }
        ).then((response) => {
            const responseHandler = ComponentInjector.get('App.ResponseHandler')
            let reponseUpdate = null
            if (responseHandler && (reponseUpdate = responseHandler(response))) {
                return reponseUpdate
            }

            this.handleFrontasticRedirect(response)

            const contentType = response.headers.get('Content-Type')
            if (contentType && contentType.includes('application/json')) {
                return response.json().then((json) => {
                    return {
                        ok: response.ok,
                        status: response.status,
                        json: json,
                    }
                })
            }

            // eslint-disable-next-line no-console
            console.trace('Unhandled Response Type:', response)
            throw new Error({ status: 500, message: 'Internal Server Error' })
        }).then((response) => {
            if (response.json && response.json['__DEBUG']) {
                logDebugStatements(response.json['__DEBUG'], method, route)
            }
            if (response.ok) {
                return response.json
            }

            if (response.json &&
                response.json.message &&
                (typeof response.json.message === 'string')) {
                // eslint-disable-next-line no-console
                console.error('Error:', response.json)
                throw new Error(response.json)
            } else {
                // eslint-disable-next-line no-console
                console.error('Unhandled Error:', response)
                throw new Error({ status: 500, message: 'Internal Server Error' })
            }
        }).then((response) => {
            if (success) {
                success(response, parameters)
            }
            return response
        }).catch((e) => {
            if (error) {
                error(e)
            }

            if (parameters && !parameters.hasError && !parameters.ownErrorHandler) {
                this.trigger('Frontastic.Frontend.Master.Error.view', { hasError: true }, 'error')
            } else if (!error) {
                throw e
            }
        })
    }

    this.requestContinuosly = function (method, route, parameters, success, error) {
        const refreshInterval = this.getReloadInterval()
        const refreshFunction = () => {
            this.request(method, route, parameters, null, success, error)
        }

        refreshFunction()
        intervals.push({
            refreshFunction: refreshFunction,
            refreshInterval: refreshInterval,
            interval: window.setInterval(refreshFunction, refreshInterval),
        })
    }

    this.trigger = (route, parameters, actionId = null) => {
        let routeKey = route.substr(route.indexOf('.') + 1)

        return this.request(
            'GET',
            route,
            parameters,
            null,
            (data, parameters) => {
                this.store.dispatch({
                    type: routeKey + '.success',
                    id: actionId,
                    cacheKey: UrlContext.getActionHash(parameters),
                    data: data,
                    parameters: parameters,
                })
            },
            (error) => {
                this.store.dispatch({
                    type: routeKey + '.error',
                    id: actionId,
                    cacheKey: UrlContext.getActionHash(parameters),
                    error: error,
                })
            }
        )
    }

    this.triggerContinuously = (route, parameters, cacheKey = null) => {
        let routeKey = route.substr(route.indexOf('.') + 1)

        this.requestContinuosly(
            'GET',
            route,
            parameters,
            (data) => {
                this.store.dispatch({
                    type: routeKey + '.success',
                    id: cacheKey,
                    data: data,
                })
            },
            (error) => {
                this.store.dispatch({
                    type: routeKey + '.error',
                    id: cacheKey,
                    error: error,
                })
            }
        )
    }

    this.pauseRequests = function () {
        if (!intervals) {
            return
        }

        for (let i = 0; i < intervals.length; ++i) {
            window.clearInterval(intervals[i].interval)
            intervals[i].interval = null
        }
    }

    this.resumeRequests = function () {
        if (!intervals) {
            return
        }

        for (let i = 0; i < intervals.length; ++i) {
            intervals[i].interval = window.setInterval(
                intervals[i].refreshFunction,
                intervals[i].refreshInterval
            )
        }
    }

    this.clearContinuousRequests = function () {
        this.pauseRequests()
        intervals = []
    }

    this.getReloadInterval = function () {
        return 5 * 60 * 1000
    }

    /**
     * Handles the Frontastic-Location Header, which will do a redirect within the PWA
     *
     * The Frontastic-Location header is similar to an traditional Location: header,
     * but is being used for API calls, where a Location header might look like the API
     * has been moved. The Frontastic-Location header advises the Frontastic SPA to redirect to a new URl.
     *
     * @param {Response} response
     * @returns {void}
     */
    this.handleFrontasticRedirect = function (response) {
        if (!response.headers.has('Frontastic-Location')) {
            return
        }

        this.router.history.push(response.headers.get('Frontastic-Location'))
    }
}

export default Api
