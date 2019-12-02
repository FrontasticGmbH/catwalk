import React from 'react'

import _ from 'lodash'

import Wishlist from '../../domain/wishlist'
import app from '../app'
import Entity from '../entity'
import Message from '../message'

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
        this.api.triggerContinuously(
            'Frontastic.WishlistApi.Wishlist.get',
            // Own error handler without error handler => Ignore all errors
            _.extend(
                { ownErrorHandler: true },
                parameters
            )
        )
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
                // FIXME: Also remove? See further calls to loadMaster() in this file
                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)

                app.getLoader('context').notifyUser(
                    <Message
                        code='account.message.wishlist'
                        parameters={{ wishlist: name }}
                        message={'Created wishlist: ' + name}
                    />,
                    'success'
                )
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.error',
                    error: error,
                })
            }
        )
    }

    this.add = (product, variant, count, wishlist = null) => {
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.WishlistApi.Wishlist.add',
                { wishlist: wishlist, ownErrorHandler: true },
                { product, variant, count },
                (data) => {
                    // FIXME: No idea why this was here. Caused re-ordering of products in normal node. Can be removed?
                    // let route = this.store.getState().app.route
                    // app.getLoader('node').loadMaster(route.route, route.parameters)
                    app.getLoader('context').notifyUser(
                        <Message
                            code='account.message.wishlistAdd'
                            message='Added product to wishlist'
                        />,
                        'success'
                    )
                    this.store.dispatch({
                        type: 'WishlistApi.Wishlist.add.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'WishlistApi.Wishlist.add.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.addMultiple = (lineItems) => {
        this.store.dispatch({
            type: 'WishlistApi.Wishlist.loading',
        })

        this.api.request(
            'POST',
            'Frontastic.WishlistApi.Wishlist.addMultiple',
            { ownErrorHandler: true },
            { lineItems },
            (data) => {
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'WishlistApi.Wishlist.add.error',
                    error: error,
                })
            }
        )
    }

    this.updateLineItem = (wishlist, update) => {
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.WishlistApi.Wishlist.updateLineItem',
                { wishlist: wishlist, ownErrorHandler: true },
                update,
                (data) => {
                    // FIXME: Also remove? See further calls to loadMaster() in this file
                    let route = this.store.getState().app.route
                    app.getLoader('node').loadMaster(route.route, route.parameters)
                    this.store.dispatch({
                        type: 'WishlistApi.Wishlist.update.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'WishlistApi.Wishlist.update.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.removeLineItem = (wishlist, update) => {
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.WishlistApi.Wishlist.removeLineItem',
                { wishlist: wishlist, ownErrorHandler: true },
                update,
                (data) => {
                    // FIXME: No idea why this was here. Caused re-ordering of products in normal node. Can be removed?
                    // let route = this.store.getState().app.route
                    // app.getLoader('node').loadMaster(route.route, route.parameters)
                    app.getLoader('context').notifyUser(
                        <Message
                            code='account.message.wishlistRemove'
                            message='Removed product from wishlist'
                        />,
                        'success'
                    )
                    this.store.dispatch({
                        type: 'WishlistApi.Wishlist.update.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'WishlistApi.Wishlist.update.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
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
