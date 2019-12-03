import React from 'react'
import ReactDOM from 'react-dom'
import _ from 'lodash'

import app from './app/app'
import store from './app/store'
import Context from './app/context'
import AppComponent from './appComponent'

export default (mountNode, dataNode, tastics = null) => {
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
        store.dispatch({
            type: 'Frontastic.RenderContext.ClientSideDetected',
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

        dispatchViewportDimensions();
        window.addEventListener('resize', _.throttle(dispatchViewportDimensions, 500))

        let context = new Context(data)

        app.getRouter().setContext(context)
        app.getRouter().setRoutes(context.routes)

        app.loadForLocation(window.location)
    } else {
        app.getLoader('context').refresh()
    }

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
