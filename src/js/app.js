import React from 'react'
import ReactDOM from 'react-dom'
import _ from 'lodash'
import { Router } from 'react-router-dom'

import app from './app/app'
import logDebugStatements from './app/logDebugStatements'
import createStore from './app/store'
import Context from './app/context'
import AppComponent from './appComponent'
import FrontasticRoute from './app/route'
import { trackingPageView } from './app/loader/node'

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

    let props = JSON.parse(dataNode.getAttribute('data-props'))
    let context = new Context(props.context)

    if (context.isDevelopment() && dataNode.hasAttribute('data-debug')) {
        logDebugStatements(
            JSON.parse(dataNode.getAttribute('data-debug')),
            'GET (most likely)', // HTTP method cannot be detected in JS
            ((window || {}).location || {}).pathname
        )
    }

    let store = createStore()
    app.initialize(store)

    app.getRouter().setContext(context)
    app.getRouter().setRoutes(context.routes)

    store.dispatch({
        type: 'FRONTASTIC_ROUTE',
        route: new FrontasticRoute(props.route),
        lastRoute: null,
    })
    store.dispatch({
        type: 'ApiBundle.Api.context.success',
        data: props.context,
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

    // Initially, we dispatch with the UserAgent that the SSR used,
    // to make sure we do not have differences during the initial hydration.
    // This is important for react, which explicitly says that hydration is only supported if both client and server render the same thing.
    // See https://github.com/facebook/react/issues/11336#issuecomment-338617882
    store.dispatch({
        type: 'Frontastic.RenderContext.UserAgentDetected',
        userAgent: dataNode.getAttribute('data-user-agent'),
    })

    store.dispatch({
        type: 'Frontend.Tastic.initialize',
        data: props.tastics,
    })
    store.dispatch({
        type: 'Frontend.Category.all.success',
        data: { categories: props.categories },
    })
    store.dispatch({
        type: 'Frontend.Facet.all.success',
        data: props.facets,
    })

    store.dispatch({
        type: 'Frontend.Node.initialize',
        data: props,
    })

    store.dispatch(trackingPageView({
        nodeId: props.node.nodeId,
        data: {
            node: props.node,
            page: props.page,
            data: props.data,
        },
        isMasterPage: props.route && props.route.route && props.route.route.includes('.Master.'),
    }))

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

        const renderRouter = (children) => {
            return (
                <Router history={app.history}>
                    {children}
                </Router>
            )
        }

        app.getLoader('cart').get()
        app.getLoader('wishlist').get()

        store.dispatch({
            type: 'Frontastic.ClientSideHydration',
        })

        ReactDOM.hydrate(
            <AppComponent app={app} renderRouter={renderRouter} />,
            mountNode,
            () => {
                // Only after hydration we switch from SSR mode to Client Side mode,
                // to make sure we do not have differences during the initial hydration.
                // This is important for react, which explicitly says that hydration is only supported if both client and server render the same thing.
                store.dispatch({
                    type: 'Frontastic.RenderContext.ClientSideDetected',
                })

                store.dispatch({
                    type: 'Frontastic.RenderContext.UserAgentDetected',
                    userAgent: navigator.userAgent,
                })

                dispatchViewportDimensions()
                window.addEventListener('resize', _.throttle(dispatchViewportDimensions, 500))

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
        return (error && error.length && typeof error[0] === 'string' && !!error[0].match(/^Warning: /))
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
