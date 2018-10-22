import app from '../app'
import Context from '../context'

/**
 * Special context loader
 */
let Loader = function (store, api) {
    this.store = store
    this.api = api

    this.refresh = (parameters) => {
        this.api.request(
            'GET',
            'Frontastic.ApiCoreBundle.Api.context',
            parameters,
            null,
            (data) => {
                let context = new Context(data)

                app.getRouter().setContext(context)
                app.getRouter().setRoutes(context.routes)

                this.store.dispatch({
                    type: 'ApiBundle.Api.context.success',
                    data: data,
                })
                app.loadForLocation(window.location)
            },
            (error) => {
                this.store.dispatch({
                    type: 'ApiBundle.Api.context.error',
                    data: error,
                })
            }
        )
    }
}

export default Loader
