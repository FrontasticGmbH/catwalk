import React from 'react'
import ReactDOM from 'react-dom'
import _ from 'lodash'

import app from './app/app'
import createStore from './app/store'
import Context from './app/context'
import AppComponent from './appComponent'

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
        let store = createStore()
        app.initialize(store)

        store.dispatch({
            type: 'ApiBundle.Api.context.success',
            data: data,
        })
        store.dispatch({
            type: 'Frontastic.RenderContext.ClientSideDetected',
        })
        store.dispatch({
            type: 'Frontastic.RenderContext.UserAgentDetected',
            userAgent: navigator.userAgent,
        })
        const dispatchViewportDimensions = () => {
            store.dispatch({
                type: 'Frontastic.RenderContext.ViewportDimensionChanged',
                viewportDimension: {
                    width: window.innerWidth,
                    height: window.innerHeight,
                },
            })
        }

        dispatchViewportDimensions()
        window.addEventListener('resize', _.throttle(dispatchViewportDimensions, 500))

        let context = new Context(data)

        app.getRouter().setContext(context)
        app.getRouter().setRoutes(context.routes)

        app.loadForLocation(window.location)
    } else {
        app.getLoader('context').refresh()
    }

    import('history').then(({ createBrowserHistory }) => {
        const isDevelopment = app.getRouter().getContext().isDevelopment()

        let beforeHydrateHtml = null
        let afterHydrateHtml = null
        if (isDevelopment && !isIE()) {
            beforeHydrateHtml = document.getElementsByTagName('body')[0].outerHTML
            consoleCaptureStart()
        }

        const history = createBrowserHistory()
        history.listen(app.loadForLocation)

        app.history = history
        app.router.history = history

        ReactDOM.hydrate(
            <AppComponent app={app} />,
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
    })
}

function hasHydrationWarning (errors) {
    return errors.map((error) => {
        return (error && error.length && !!error[0].match(/^Warning: /))
    }).reduce((accumulator, currentValue) => {
        return accumulator || currentValue
    }, false)
}

function isIE () {
    // eslint-disable-next-line
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

export default appCreator
