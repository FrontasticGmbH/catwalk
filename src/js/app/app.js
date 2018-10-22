import store from './store'
import history from './history'
import Router from './router'
import Api from './api'
import Loader from './loader'

import { VisibilityChange, httpParseQuery } from 'frontastic-common'

let App = function (store, history) {
    this.store = store
    this.history = history
    this.router = new Router(this.history, {
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

    this.getApi = function () {
        return this.api
    }

    this.getRouter = function () {
        return this.router
    }

    this.getStore = function () {
        return this.store
    }

    this.getLoader = function (name) {
        return this.loader.getLoader(name)
    }

    if (this.history) {
        this.history.listen(this.loadForLocation)
    }
}

export default new App(store, history)
