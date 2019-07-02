import store from './store'
import Router from './router'
import Api from './api'
import Loader from './loader'

import { VisibilityChange, httpParseQuery } from 'frontastic-common'

let App = function (store) {
    this.store = store
    this.history = null
    this.router = new Router(null, {
        'Frontastic.ApiBundle.Api.context': '/api/context',
    })
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
        return this.store
    }

    this.getLoader = function (name) {
        return this.loader.getLoader(name)
    }

    if (typeof window !== 'undefined') {
        import('history/createBrowserHistory').then((createHistory) => {
            this.history = createHistory()
            this.history.listen(this.loadForLocation)

            this.router = new Router(this.history, {
                'Frontastic.ApiBundle.Api.context': '/api/context',
            })
        })
    }
}

export default new App(store)
