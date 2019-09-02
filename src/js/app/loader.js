import Route from './route'

import DevLoader from './loader/dev'
import ContextLoader from './loader/context'
import NodeLoader from './loader/node'
import TasticLoader from './loader/tastic'
import DataLoader from './loader/data'
import FacetLoader from './loader/facet'
import CategoryLoader from './loader/category'
import CartLoader from './loader/cart'
import WishlistLoader from './loader/wishlist'

/**
 * This class defines which content is to be loaded and written into the global
 * store for which route.
 *
 * For each route there should be a set of load operations which will then
 * populate the global store. The route parameters are parsed from the current
 * path and usually determine the exact entities to load. Thus the route
 * parameters are usually passed to the load() method.
 *
 * If you require re-mapping of those parameters this shiould not be done in
 * here, but in the dedicated loader class.
 */
let Loader = function (store, router, api) {
    this.store = store
    this.router = router
    this.api = api

    this.loaders = {
        dev: new DevLoader(this.store, this.api),
        context: new ContextLoader(this.store, this.api),
        node: new NodeLoader(this.store, this.api),
        tastic: new TasticLoader(this.store, this.api),
        cart: new CartLoader(this.store, this.api),
        wishlist: new WishlistLoader(this.store, this.api),
        data: new DataLoader(this.store, this.api),
        facet: new FacetLoader(this.store, this.api),
        category: new CategoryLoader(this.store, this.api),
    }

    this.loadContentForPath = function (pathname, query = {}, historyState = null) {
        this.api.clearContinuousRequests()

        let route = null
        try {
            route = new Route(router.match(pathname), query, historyState)
        } catch (error) {
            // eslint-disable-next-line no-console
            console.error('Routing error', error)
            route = new Route({
                route: 'Frontastic.Frontend.Master.Error.view',
                parameters: {},
            }, query, historyState)
        }
        this.store.dispatch({
            type: 'FRONTASTIC_ROUTE',
            route: route,
            lastRoute: this.store.getState().app.route,
        })

        if (typeof PRODUCTION === 'undefined' || !PRODUCTION) { // eslint-disable-line no-undef
            // eslint-disable-next-line no-console
            console.groupCollapsed('%c🔀 %c%s', 'color: gray', 'color: darkmagenta', route.route)
            // eslint-disable-next-line no-console
            console.info(route)
            // eslint-disable-next-line no-console
            console.groupEnd()
        }

        this.loaders.cart.get()
        this.loaders.wishlist.get()

        switch (route.route) {
        case 'Frontastic.Frontend.Master.Checkout.finished':
            this.loaders.cart.getOrder(route.parameters)
            this.loaders.node.loadMaster(route.route, route.parameters)
            break
        case 'Frontastic.Frontend.Preview.view':
            this.loaders.node.reloadPreview(route.parameters)
            break
        case 'Frontastic.Frontend.PatternLibrary.overview':
            this.loaders.dev.loadTunnel(route.parameters)
            break
        default:
            if (route.route.substr(0, 5) === 'node_') {
                route.parameters.nodeId = route.route.substr(5).split('.')[0]
                this.loaders.node.loadNode(route.parameters)
            } else if (route.route.includes('.Master.')) {
                this.loaders.node.loadMaster(route.route, route.parameters)
            }
        }
    }

    this.getLoader = function (name) {
        if (!(name in this.loaders)) {
            throw new Error('Unknown loader ' + name)
        }

        return this.loaders[name]
    }
}

export default Loader
