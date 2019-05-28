import React from 'react'
import ReactDOM from 'react-dom'
import { Router, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'

import defaultTastics from './tastic/tastics'
import app from './app/app'
import IntlProvider from './app/intlProvider'
import store from './app/store'
import Context from './app/context'
import history from './app/history'

import Preview from './preview'
import PatternLibrary from './patternLibrary'
import Patterns from './patternLibrary/patterns'
import Node from './node'

export default (mountNode, tastics = null) => {
    if (!mountNode) {
        return
    }

    window.tastics = tastics || defaultTastics

    let appData = mountNode.getAttribute('data-app')
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

    ReactDOM.hydrate(
        <Provider store={app.getStore()}>
            <IntlProvider>
                <Router history={history}>
                    <Switch>
                        <Route
                            exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                            component={Preview}
                        />

                        <Route
                            exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.PatternLibrary.overview')}
                            component={PatternLibrary}
                        />
                        <Route
                            exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.PatternLibrary.view')}
                            component={Patterns}
                        />

                        <Route component={Node} />
                    </Switch>
                </Router>
            </IntlProvider>
        </Provider>,
        mountNode
    )
}
