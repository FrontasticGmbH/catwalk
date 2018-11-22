import _ from 'lodash'

import Wishlist from '../../domain/wishlist'
import app from '../app'
import Entity from '../entity'

/**
 * Loader classes like this consilidate all loading monitors for a domain
 * concept.
 *
 * They define one (or multiple) load() methods which execute an AJAX call
 * through the Api. You may implement parameter remapping and cache checks in
 * here. For the loaded date there should be a dispatched action through Redux.
 *
 * The Loader also defines a (static) method which handles its own actions and
 * applies the corresponding changes to the global store state.
 */
let WishlistLoader = function (store, api) {
    this.store = store
    this.api = api

    this.get = (parameters = {}) => {
        this.api.triggerContinuously('Frontastic.WishlistApi.Wishlist.get', parameters)
    }

    this.create = (name) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.create',
            null,
            { name: name },
            (data) => {
                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)

                app.getLoader('context').notifyUser('Created wishlist: ' + name, 'success', 5000)
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser('Failed to create wishlist: ' + error.message, 'error')
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.error',
                    error: error,
                })
            }
        )
    }

    this.add = (product, variant, count, wishlist = null) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.add',
            { wishlist: wishlist, ownErrorHandler: true },
            { product, variant, count },
            (data) => {
                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)

                app.getLoader('context').notifyUser('Added product to wishlist', 'success', 5000)
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser('Failed to add product to wishlist: ' + error.message, 'error')
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.error',
                    error: error,
                })
            }
        )
    }

    this.updateLineItem = (wishlist, update) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.updateLineItem',
            { wishlist: wishlist, ownErrorHandler: true },
            update,
            (data) => {
                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)

                app.getLoader('context').notifyUser('Updated product count', 'success', 5000)
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser('Failed to updated product count: ' + error.message, 'error')
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.error',
                    error: error,
                })
            }
        )
    }

    this.removeLineItem = (wishlist, update) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.removeLineItem',
            { wishlist: wishlist, ownErrorHandler: true },
            update,
            (data) => {
                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)

                app.getLoader('context').notifyUser('Removed product from wishlist', 'success', 5000)
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser('Failed to removed product from wishlist: ' + error.message, 'error')
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.error',
                    error: error,
                })
            }
        )
    }
}

const initialGlobalState = {
    wishlist: null,
}

WishlistLoader.handleAction = (globalState = initialGlobalState, action) => {
    let wishlist = null

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            wishlist: Entity.purge(globalState.wishlist),
        }

    case 'WishlistApi.Wishlist.loading':
        wishlist = new Entity(globalState.wishlist.data)
        wishlist.loading = true

        return {
            ...globalState,
            wishlist: wishlist,
        }

    case 'WishlistApi.Wishlist.get.success':
    case 'WishlistApi.Wishlist.add.success':
    case 'WishlistApi.Wishlist.update.success':
        return {
            ...globalState,
            wishlist: new Entity(new Wishlist(action.data.wishlist)),
        }
    case 'WishlistApi.Wishlist.get.error':
    case 'WishlistApi.Wishlist.add.error':
    case 'WishlistApi.Wishlist.update.error':
    case 'WishlistApi.Wishlist.checkout.error':
        return {
            ...globalState,
            wishlist: new Entity(globalState.wishlist.data).setError(action.error),
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default WishlistLoader
