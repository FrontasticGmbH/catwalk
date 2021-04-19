import Express from 'express'
import bodyParser from 'body-parser'
import React from 'react'
import { renderToString } from 'react-dom/server'
import { StaticRouter } from 'react-router-dom'

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

const defaultRenderWrapper = (appComponent) => {
    return {
        app: renderToString(appComponent),
    }
}

/**
 * @typedef {Object} RenderData
 * @property {string} app - The server-side-rendered react component. Most likely just by calling `renderToString(appComponent)`
 * @property {string} styles - The server side styles that should be passed to `react-helmet`.
 */

/**
 * @callback renderWrapper
 * @param {React.Component} appComponent
 * @return {RenderData}
 */

/**
 *
 * @param {Array} tastics - The Array of Tastics (default: null)
 * @param {Number} port - The port on which the server should run (default: 8000)
 * @param {renderWrapper} renderWrapper - The renderWrapper method that renders the app and could be used to hook in for generating the StyledComponents SSR result
 */
export default (tastics = null, port = 8000, renderWrapper = defaultRenderWrapper) => {
    global.tastics = tastics
    global.btoa = (b) => {
        return Buffer.from(b).toString('base64')
    }

    // We increase the body data limit because we can recieve quite some data from
    // Catwalk inclduing product lists, etc.
    express.use(bodyParser.json({ limit: '10MB' }))
    express.use(handleRender)

    function handleRender(request, response) {
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
            type: 'Frontastic.RenderContext.UserAgentDetected',
            userAgent: request.get('User-Agent'),
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

        const renderData = renderWrapper(
            <AppComponent app={app} renderRouter={renderRouter} />
        )

        let helmetMetaData = {}
        let helmetRenderResult = Helmet.renderStatic()
        for (let [property, value] of Object.entries(helmetRenderResult)) {
            value = value.toString()

            if (value) {
                helmetMetaData[property] = value
            }
        }

        response.send({
            app: renderData.app,
            helmet: {
                title: '',
                meta: '',
                styles: renderData.styles || '',
                ...helmetMetaData,
            },
        })
    }

    express.listen(port)
}
