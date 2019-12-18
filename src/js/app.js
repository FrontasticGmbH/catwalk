import React from 'react'
import ReactDOM from 'react-dom'
import _ from 'lodash'

import app from './app/app'
import store from './app/store'
import Context from './app/context'
import AppComponent from './appComponent'
import FrontasticRoute from './app/route'

export default (mountNode, props, tastics = null) => {
    if (!mountNode || !props) {
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

    if (props instanceof HTMLElement) {
        props = props.getAttribute('data-props')
        props = JSON.parse(props)
    }

    let context = new Context(props.context)

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

    store.dispatch({
        type: 'Frontastic.RenderContext.ClientSideDetected',
    })
    store.dispatch({
        type: 'Frontastic.RenderContext.UserAgentDetected',
        userAgent: navigator.userAgent,
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

    import('history').then(({ createBrowserHistory }) => {
        const history = createBrowserHistory()
        history.listen(app.loadForLocation)

        app.history = history
        app.router.history = history

        ReactDOM.hydrate(
            <AppComponent app={app} />,
            mountNode
        )
    })
}
