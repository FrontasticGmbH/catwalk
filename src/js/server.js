import Express from 'express'
import bodyParser from 'body-parser'
import React from 'react'
import { renderToString } from 'react-dom/server'
import { StaticRouter, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'
import _ from 'lodash'

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

// We increase the body data limit because we can recieve quite some data from
// the BFF inclduing product lsits, etc.
express.use(bodyParser.json({ limit: '1MB' }))
express.use(handleRender)

function handleRender (request, response) {
    let context = new Context(request.body.context)
    let props = request.body.props

    store.dispatch({
        type: 'ApiBundle.Api.context.success',
        data: request.body.context,
    })

    app.getRouter().setContext(context)
    app.getRouter().setRoutes(context.routes)

    let wrapComponent = (Component, props) => {
        return () => {
            return <Component {...props} />
        }
    }

    response.send(
        renderToString(
            <Provider store={store}>
                <StaticRouter location={request.url} context={{}}>
                    <Switch>
                        <Route exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                            component={wrapComponent(Preview, props)}
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
                                    component={wrapComponent(Node, props)}
                                />)
                            }
                        ))}

                        <Route exact
                            key='Frontastic.Frontend.Master.Product.view'
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Master.Product.view')}
                            component={wrapComponent(Node, props)}
                        />
                        <Route exact
                            key='Frontastic.Frontend.Master.Category.view'
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Master.Category.view')}
                            component={wrapComponent(Node, props)}
                        />

                        <Route component={() => {
                            console.error('No route found.')
                            return null
                        }} />
                    </Switch>
                </StaticRouter>
            </Provider>
        )
    )
}

express.listen(port)
