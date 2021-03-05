import Express from 'express'

// @TODO: Fork: http://rowanmanning.com/posts/node-cluster-and-express/
// @TODO: Supervise forks
const express = Express()
const PORT = process.env.PORT || 8000

const defaultRenderWrapper = (appComponent) => { // TODO: share
    return {
        app: renderToString(appComponent),
    }
}

//TODO: share
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
 * @param {Object} tastics - A key-value pair object, where the key is the projectName (for example demo_project) and value the tastics. Optional on a by project basis.
 * @param {Object} renderWrappers - A key-value pair object, where the key is the projectName (for example demo_project) and value the renderWrapper. Optional on a by project basis.
 */
export default (projectsTastics = {}, projectsRenderWrappers = {}) => {
    //global.tastics = tastics //TODO: figure out how to handle this 
    global.btoa = (b) => {
        return Buffer.from(b).toString('base64')
    }

    //TODO: find way to split shared logic
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

        const renderData = renderWrapper( //TODO: handle sharing this
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

    express.listen(PORT)
}
