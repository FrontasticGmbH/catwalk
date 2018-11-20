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

    this.add = (product, variant, count, option = null) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.add',
            null,
            { product, variant, count, option },
            (data) => {
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.success',
                    data: data,
                })
            },
            (error) => {
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.error',
                    error: error,
                })
            }
        )
    }

    this.updateLineItem = (update) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.updateLineItem',
            { ownErrorHandler: true },
            update,
            (data) => {
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.success',
                    data: data,
                })
            },
            (error) => {
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.error',
                    error: error,
                })
            }
        )
    }

    this.removeLineItem = (update) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.removeLineItem',
            { ownErrorHandler: true },
            update,
            (data) => {
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.update.success',
                    data: data,
                })
            },
            (error) => {
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
