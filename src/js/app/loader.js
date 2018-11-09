import Route from './route'

import ContextLoader from './loader/context'
import NodeLoader from './loader/node'
import TasticLoader from './loader/tastic'
import DataLoader from './loader/data'
import FacetLoader from './loader/facet'
import CategoryLoader from './loader/category'

import CartLoader from '../domain/cartLoader'

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
        context: new ContextLoader(this.store, this.api),
        node: new NodeLoader(this.store, this.api),
        tastic: new TasticLoader(this.store, this.api),
        cart: new CartLoader(this.store, this.api),
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

        if (typeof PRODUCTION === 'undefined') {
            console.groupCollapsed('%c🔀 %c%s', 'color: gray', 'color: darkmagenta', route.route)
            console.info(route)
            console.groupEnd()
        }

        // TODO: This re-loads tastic config on every request. Should be done in the background continuously or once?
        this.loaders.tastic.loadTastics(route.parameters)
        this.loaders.facet.loadFacets(route.parameters)
        this.loaders.category.loadCategories(route.parameters)
        this.loaders.cart.get()

        switch (route.route) {
        case 'Frontastic.Frontend.Master.Checkout.finished':
            this.loaders.cart.getOrder(route.parameters)
            this.loaders.node.loadMaster(route.route, route.parameters)
            break
        case 'Frontastic.Frontend.Master.Category.view':
        case 'Frontastic.Frontend.Master.Product.view':
        case 'Frontastic.Frontend.Master.Checkout.cart':
        case 'Frontastic.Frontend.Master.Checkout.checkout':
        case 'Frontastic.Frontend.Master.Account.index':
        case 'Frontastic.Frontend.Master.Account.forgotPassword':
        case 'Frontastic.Frontend.Master.Account.confirm':
        case 'Frontastic.Frontend.Master.Account.profile':
        case 'Frontastic.Frontend.Master.Account.addresses':
        case 'Frontastic.Frontend.Master.Account.orders':
        case 'Frontastic.Frontend.Master.Account.wishlists':
        case 'Frontastic.Frontend.Master.Account.vouchers':
        case 'Frontastic.Frontend.Master.Error.view':
            this.loaders.node.loadMaster(route.route, route.parameters)
            break
        case 'Frontastic.Frontend.Preview.view':
            this.loaders.node.reloadPreview(route.parameters)
            break
        default:
            if (route.route.substr(0, 5) === 'node_') {
                route.parameters.nodeId = route.route.substr(5)
                this.loaders.node.loadNode(route.parameters)
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
