import React from 'react'
import _ from 'lodash'

import Cart from '../../domain/cart'
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
let CartLoader = function (store, api) {
    this.store = store
    this.api = api

    this.get = (parameters = {}) => {
        this.api.triggerContinuously(
            'Frontastic.CartApi.Cart.get',
            // Own error handler without error handler => Ignore all errors
            _.extend(
                { ownErrorHandler: true },
                parameters
            )
        )
    }

    this.getOrder = (parameters = {}) => {
        this.api.trigger('Frontastic.CartApi.Cart.getOrder', parameters)
    }

    this.setProductOption = (productId, option) => {
        this.store.dispatch({
            type: 'CartApi.Cart.productOption',
            productId: productId,
            option: option,
        })
    }

    /**
     * Product is here for historical reasons, please submit null as first value.
     */
    this.add = (product = null, variant, count, option = null) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        // TODO: Add to remainder methods
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.add',
                { ownErrorHandler: true },
                { variant, count, option },
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.add.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.add.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.addMultiple = (lineItems) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.addMultiple',
                { ownErrorHandler: true },
                { lineItems },
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.add.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.add.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.updateLineItem = (update) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.updateLineItem',
                { ownErrorHandler: true },
                update,
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.removeLineItem = (update) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.removeLineItem',
                { ownErrorHandler: true },
                update,
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.remove.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.remove.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.updateCart = (cartInformation) => {
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.update',
                { ownErrorHandler: true },
                cartInformation,
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.success',
                        data: data,
                    })
                    resolve()
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.redeemDiscount = (code) => {
        if (!code) {
            return
        }

        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.redeemDiscount',
                { ownErrorHandler: true, code: code },
                null,
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.success',
                        data: data,
                    })
                    resolve(data)
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.removeDiscount = (discountId) => {
        if (!discountId) {
            return
        }

        // TS, 2019-08-02: Permitted adjustment. Maybe merge upstream?
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.removeDiscount',
                { ownErrorHandler: true },
                { discountId: discountId },
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.success',
                        data: data,
                    })
                    resolve(data)
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    this.store.dispatch({
                        type: 'CartApi.Cart.update.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        })
    }

    this.checkout = (cartInformation) => {
        return new Promise((resolve, reject) => {
            this.api.request(
                'POST',
                'Frontastic.CartApi.Cart.checkout',
                { ownErrorHandler: true },
                cartInformation,
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.checkout.success',
                        data: data,
                    })
                    resolve(data)
                },
                (error) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.checkout.error',
                        error: error,
                    })
                    reject(error)
                }
            )
        }).then((data) => {
            app.getRouter().push(
                'Frontastic.Frontend.Master.Checkout.finished',
                {
                    order: data.order.orderId,
                    token: _.get(data, 'order.custom.viewToken', null),
                }
            )
            return data
        }, (error) => {
            app.getLoader('context').notifyUser(<Message {...error} />, 'error')
            return error
        })
    }
}

const initialGlobalState = {
    cart: null,
    orders: {},
    lastOrder: null,
    productOptions: {},
}

CartLoader.handleAction = (globalState = initialGlobalState, action) => {
    let cart = null
    let orders = {}
    let productOptions = {}

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            cart: Entity.purge(globalState.cart),
            orders: Entity.purgeMap(globalState.orders),
            lastOrder: Entity.purge(globalState.lastOrder),
            productOptions: globalState.productOptions,
        }

    case 'CartApi.Cart.loading':
        cart = new Entity(globalState.cart.data)
        cart.loading = true

        return {
            ...globalState,
            cart: cart,
        }

    case 'CartApi.Cart.productOption':
        productOptions = _.extend(productOptions, globalState.productOptions)
        productOptions[action.productId] = action.option

        return {
            ...globalState,
            productOptions: productOptions,
        }

    case 'CartApi.Cart.get.success':
    case 'CartApi.Cart.add.success':
    case 'CartApi.Cart.remove.success':
    case 'CartApi.Cart.update.success':
        return {
            ...globalState,
            cart: new Entity(new Cart(action.data.cart)),
        }
    case 'CartApi.Cart.get.error':
    case 'CartApi.Cart.add.error':
    case 'CartApi.Cart.remove.error':
    case 'CartApi.Cart.update.error':
    case 'CartApi.Cart.checkout.error':
        return {
            ...globalState,
            cart: new Entity(globalState.cart.data).setError(action.error),
        }

    case 'CartApi.Cart.checkout.success':
        orders = _.extend(orders, globalState.orders)
        orders[action.data.order.orderId] = new Entity(action.data.order)

        return {
            ...globalState,
            cart: null,
            lastOrder: new Entity(new Cart(action.data.order)),
            orders: orders,
        }

    case 'CartApi.Cart.getOrder.success':
        orders = _.extend(orders, globalState.orders)
        orders[action.data.order.orderId] = new Entity(action.data.order)

        return {
            ...globalState,
            orders: orders,
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default CartLoader
