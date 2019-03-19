import Express from 'express'
import bodyParser from 'body-parser'
import React from 'react'
import { renderToString } from 'react-dom/server'
import { StaticRouter, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'
import _ from 'lodash'

import defaultTastics from './tastic/tastics'
import app from './app/app'
import store from './app/store'
import Context from './app/context'

import Preview from './preview'
import Node from './node'

// @TODO: Fork: http://rowanmanning.com/posts/node-cluster-and-express/
// @TODO: Supervise forks
const express = Express()
// @TODO: Only listen on localhost?
const port = 8000

export default (tastics = null) => {
    global.tastics = tastics || defaultTastics

    // We increase the body data limit because we can recieve quite some data from
    // Catwalk inclduing product lists, etc.
    express.use(bodyParser.json({ limit: '1MB' }))
    express.use(handleRender)

    function handleRender (request, response) {
        let context = new Context(request.body.context)
        let props = request.body.props

        // This usually is done by createStore() and reading the
        // properties directly from the DOM.  This does not work, thus
        // we post-initilize the state (see app/store.js):
        store.dispatch({
            type: 'ApiBundle.Api.context.success',
            data: request.body.context,
        })
        store.dispatch({
            type: 'Frontend.Node.initialize',
            data: props,
        })
        store.dispatch({
            type: 'Frontend.Tastic.initialize',
            data: props.tastics,
        })

        app.getRouter().setContext(context)
        app.getRouter().setRoutes(context.routes)

        response.send(
            renderToString(
                <Provider store={store}>
                    <StaticRouter location={request.url} context={{}}>
                        <Switch>
                            <Route exact
                                path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                                component={Preview}
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
                    </StaticRouter>
                </Provider>
            )
        )
    }

    express.listen(port)
}
