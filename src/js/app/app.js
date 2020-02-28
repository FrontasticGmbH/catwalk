import Router from './router'
import Api from './api'
import Loader from './loader'

import { VisibilityChange, httpParseQuery } from 'frontastic-common'

let App = function (store) {
    this.history = null
    this.router = new Router(null, {
        'Frontastic.ApiCoreBundle.Api.context': { path: '/api/context' },
        'Frontastic.Frontend.Preview.view': { path: '/p/{preview}' },
    })

    this.initialize = function (store) {
        this.store = store

        this.api = new Api(this.router, this.store)
        this.loader = new Loader(this.store, this.router, this.api)

        this.visibility = new VisibilityChange()
        this.visibility.registerCallBack(this.api.pauseRequests, this.api.resumeRequests)

        this.loadForLocation = (location) => {
            this.loader.loadContentForPath(
                location.pathname,
                httpParseQuery(location.search.substr(1)),
                location.state || null
            )
        }

        this.store.dispatch({
            type: 'Frontend.App.initialize',
            data: this,
        })
    }

    /**
     * @return {Api}
     */
    this.getApi = function () {
        return this.api
    }

    /**
     * @return {Router}
     */
    this.getRouter = function () {
        return this.router
    }

    this.getStore = function () {
        if (!this.store) {
            throw new Error('Store not yet defined.')
        }

        return this.store
    }

    this.getLoader = function (name) {
        return this.loader.getLoader(name)
    }
}

export default new App(null)
