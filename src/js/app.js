import React from 'react'
import ReactDOM from 'react-dom'
import { Router, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'
import _ from 'lodash'

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

    ReactDOM.render(
        (<Provider store={app.getStore()}>
            <IntlProvider>
                <Router history={history}>
                    <Switch>
                        <Route exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                            component={Preview}
                        />

                        <Route exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.PatternLibrary.overview')}
                            component={PatternLibrary}
                        />
                        <Route exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.PatternLibrary.view')}
                            component={Patterns}
                        />

                        {_.toArray(_.mapValues(
                            app.getRouter().routes,
                            (path, route) => {
                                if (route.substr(0, 5) !== 'node_') {
                                    return null
                                }

                                return (<Route exact
                                    key={route}
                                    path={app.getRouter().reactRoute(route)}
                                    component={Node}
                                />)
                            }
                        ))}

                        <Route exact path='/' component={() => {
                            return (<div style={{
                                maxWidth: '768px',
                                margin: '50px auto',
                            }}>
                                <h1 className='c-heading-beta'>Frontastic Local Development</h1>
                                <div className='c-alert c-alert--info'>
                                    <p className='c-alert__message'>You can find <a href='/_patterns'>our Patternlab under /_patterns</a>.</p>
                                </div>
                            </div>)
                        }} />

                        <Route component={Node} />
                    </Switch>
                </Router>
            </IntlProvider>
        </Provider>),
        mountNode
    )
}
