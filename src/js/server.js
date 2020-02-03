import Express from 'express'
import bodyParser from 'body-parser'
import React from 'react'
import { renderToString } from 'react-dom/server'
import { StaticRouter } from 'react-router-dom'
import _ from 'lodash'

import app from './app/app'
import createStore from './app/store'
import Context from './app/context'
import FrontasticRoute from './app/route'

import { Helmet } from 'react-helmet'
import AppComponent from './appComponent'

// @TODO: Fork: http://rowanmanning.com/posts/node-cluster-and-express/
// @TODO: Supervise forks
const express = Express()
// Defined for use in subsequent files only
const PRODUCTION = true // eslint-disable-line no-unused-vars

export default (tastics = null, port = 8000) => {
    global.tastics = tastics
    global.btoa = (b) => {
        return Buffer.from(b).toString('base64')
    }
    // We increase the body data limit because we can recieve quite some data from
    // Catwalk inclduing product lists, etc.
    express.use(bodyParser.json({ limit: '5MB' }))
    express.use(handleRender)

    function handleRender (request, response) {
        let context = new Context(request.body.context)
        let props = request.body.props
        let store = createStore()
        app.initialize(store)

        window.location.href = request.body.requestUri

        // This usually is done by createStore() and reading the
        // properties directly from the DOM.  This does not work, thus
        // we post-initilize the state (see app/store.js):
        store.dispatch({
            type: 'FRONTASTIC_ROUTE',
            route: new FrontasticRoute(props.route, request.body.queryParameters),
            lastRoute: null,
        })
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
        store.dispatch({
            type: 'Frontend.Category.all.success',
            data: { categories: props.categories },
        })
        store.dispatch({
            type: 'Frontend.Facet.all.success',
            data: props.facets,
        })

        app.getRouter().setContext(context)
        app.getRouter().setRoutes(context.routes)

        Helmet.canUseDOM = false

        const renderRouter = (children) => {
            return (
                <StaticRouter location={request.url} context={{}}>
                    {children}
                </StaticRouter>
            )
        }

        response.send({
            app: renderToString(<AppComponent app={app} renderRouter={renderRouter} />),
            helmet: {
                title: '',
                meta: '',
                ...(_.omitBy(
                    _.mapValues(
                        Helmet.renderStatic(),
                        (value, key) => {
                            return value.toString()
                        }
                    ),
                    (value) => {
                        return !value
                    }
                )),
            },
        })
    }

    express.listen(port)
}
