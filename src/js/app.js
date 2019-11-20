import React from 'react'
import ReactDOM from 'react-dom'
import { Router, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'

import app from './app/app'
import IntlProvider from './app/intlProvider'
import store from './app/store'
import Context from './app/context'

import Preview from './preview'
import Node from './node'

function appCreator (mountNode, dataNode, tastics = null) {
    if (!mountNode || !dataNode) {
        return
    }

    window.onerror = (message, url, line, column = null, error = null) => {
        try {
            fetch(
                '/_recordFrontendError',
                {
                    method: 'POST',
                    headers: new Headers({
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }),
                    body: JSON.stringify({
                        message,
                        url,
                        line,
                        stack: error && error.stack,
                    }),
                    credentials: 'same-origin',
                    cache: 'default',
                }
            )
        } catch (e) {
            // Ignore errors during recording errors
        }
    }

    window.tastics = tastics

    let appData = dataNode.getAttribute('data-app')
    if (appData) {
        let data = JSON.parse(appData)
        store.dispatch({
            type: 'ApiBundle.Api.context.success',
            data: data,
        })

        let context = new Context(data)

        app.getRouter().setContext(context)
        app.getRouter().setRoutes(context.routes)

        app.loadForLocation(window.location)
    } else {
        app.getLoader('context').refresh()
    }

    if (typeof window !== 'undefined') {
        import('history').then(({ createBrowserHistory }) => {
            const history = createBrowserHistory()
            history.listen(app.loadForLocation)

            app.history = history
            app.router.history = history

            hydrate(app, mountNode)
        })
    } else {
        hydrate(app, mountNode)
    }
}

function hydrate (app, mountNode) {
    const isDevelopment = app.getRouter().getContext().isDevelopment()

    let beforeHydrateHtml = null
    let afterHydrateHtml = null
    if (isDevelopment && !isIE()) {
        beforeHydrateHtml = document.getElementsByTagName('body')[0].outerHTML
        consoleCaptureStart()
    }

    ReactDOM.hydrate(
        <Provider store={app.getStore()}>
            <IntlProvider>
                <Router history={app.history}>
                    <Switch>
                        <Route
                            exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                            component={Preview}
                        />

                        <Route component={Node} />
                    </Switch>
                </Router>
            </IntlProvider>
        </Provider>,
        mountNode,
        () => {
            if (isDevelopment && !isIE()) {
                const diffLog = require('./app/htmlDiff/differ').default
                const printLog = require('./app/htmlDiff/printLog').default

                afterHydrateHtml = document.getElementsByTagName('body')[0].outerHTML
                if (hasHydrationWarning(consoleCaptureStop())) {
                    const log = diffLog(beforeHydrateHtml, afterHydrateHtml)

                    printLog(log)
                } else {
                    // eslint-disable-next-line
                    console.log('No hydration issue')
                }
            }
        }
    )
}

function isIE () {
    return ((window && window.navigator) && window.navigator.userAgent.indexOf('MSIE ') == !-1 || !!window.navigator.userAgent.match(/Trident.*rv\:11\./))
}

function consoleCaptureStart () {
    /* eslint-disable no-console */
    console.stderror = console.error.bind(console)
    console.errors = []
    console.error = function () {
        console.errors.push(Array.from(arguments))
        console.stderror.apply(console, arguments)
    }
    /* eslint-enable no-console */
}

function consoleCaptureStop () {
    /* eslint-disable no-console */
    if (!console.errors) {
        return []
    }

    console.error = console.stderror.bind(console)
    delete console.stderror

    const errors = console.errors
    delete console.errors
    /* eslint-enable no-console */
    return errors
}

function hasHydrationWarning (errors) {
    return errors.map((error) => {
        return (error && error.length && !!error[0].match(/^Warning: /))
    }).reduce((accumulator, currentValue) => {
        return accumulator || currentValue
    }, false)
}

export default appCreator
