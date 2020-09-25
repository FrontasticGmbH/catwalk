import React from 'react'

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

    this.getContinuously = (parameters = {}) => {
        this.api.triggerContinuously(
            'Frontastic.CartApi.Cart.get',
            // Own error handler without error handler => Ignore all errors
            {
                ownErrorHandler: true,
                ...parameters,
            }
        )
    }

    this.get = (parameters = {}) => {
        return this.api.trigger(
            'Frontastic.CartApi.Cart.get',
            // Own error handler without error handler => Ignore all errors
            {
                ownErrorHandler: true,
                ...parameters,
            }
        )
    }

    /**
     * @param parameters
     * @return Promise
     */
    this.getOrder = (parameters = {}) => {
        return this.api.trigger('Frontastic.CartApi.Cart.getOrder', parameters)
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
     *
     * @return Promise
     */
    this.add = (product = null, variant, count, option = null) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.add',
            { ownErrorHandler: true },
            { variant, count, option },
            (data) => {
                app.getLoader('context').notifyUser(
                    <Message code='account.message.cartAdd' message='Added product to cart' />,
                    'success'
                )
                this.store.dispatch({
                    type: 'CartApi.Cart.add.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.add.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param lineItems
     * @return Promise
     */
    this.addMultiple = (lineItems) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.addMultiple',
            { ownErrorHandler: true },
            { lineItems },
            (data) => {
                this.store.dispatch({
                    type: 'CartApi.Cart.add.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.add.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param cartInformation
     * @return Promise
     */
    this.addPayment = (cartInformation) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Demo.Payment.Invoice.add',
            { ownErrorHandler: true },
            cartInformation,
            (data) => {
                this.store.dispatch({
                    type: 'CartApi.Cart.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.update.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param update
     * @return Promise
     */
    this.updateLineItem = (update) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.updateLineItem',
            { ownErrorHandler: true },
            update,
            (data) => {
                this.store.dispatch({
                    type: 'CartApi.Cart.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.update.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param update
     * @return Promise
     */
    this.removeLineItem = (update) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.removeLineItem',
            { ownErrorHandler: true },
            update,
            (data) => {
                app.getLoader('context').notifyUser(
                    <Message code='account.message.cartRemove' message='Removed product from cart' />,
                    'success'
                )
                this.store.dispatch({
                    type: 'CartApi.Cart.remove.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.remove.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param cartInformation
     * @return Promise
     */
    this.updateCart = (cartInformation) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.update',
            { ownErrorHandler: true },
            cartInformation,
            (data) => {
                this.store.dispatch({
                    type: 'CartApi.Cart.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.update.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param code
     * @return Promise
     */
    this.redeemDiscount = (code) => {
        if (!code) {
            return
        }

        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.redeemDiscount',
            { ownErrorHandler: true, code: code },
            null,
            (data) => {
                this.store.dispatch({
                    type: 'CartApi.Cart.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.update.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param discountId
     * @return Promise
     */
    this.removeDiscount = (discountId) => {
        if (!discountId) {
            return
        }

        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api.request(
            'POST',
            'Frontastic.CartApi.Cart.removeDiscount',
            { ownErrorHandler: true },
            { discountId: discountId },
            (data) => {
                this.store.dispatch({
                    type: 'CartApi.Cart.update.success',
                    data: data,
                })
            },
            (error) => {
                app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                this.store.dispatch({
                    type: 'CartApi.Cart.update.error',
                    error: error,
                })
            }
        )
    }

    /**
     * @param cartInformation
     * @return Promise
     */
    this.checkout = (cartInformation) => {
        this.store.dispatch({
            type: 'CartApi.Cart.loading',
        })

        return this.api
            .request(
                'POST',
                'Frontastic.CartApi.Cart.checkout',
                { ownErrorHandler: true },
                cartInformation,
                (data) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.checkout.success',
                        data: data,
                    })
                },
                (error) => {
                    this.store.dispatch({
                        type: 'CartApi.Cart.checkout.error',
                        error: error,
                    })
                }
            )
            .then(
                (data) => {
                    app.getRouter().push('Frontastic.Frontend.Master.Checkout.finished', {
                        order: data.order.orderId,
                        token: (data.order && data.order.custom && data.order.custom.viewToken) || null,
                    })
                    return data
                },
                (error) => {
                    app.getLoader('context').notifyUser(<Message {...error} />, 'error')
                    return error
                }
            )
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
        productOptions = { ...productOptions, ...globalState.productOptions }
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
        orders = { ...orders, ...globalState.orders }
        orders[action.data.order.orderId] = new Entity(action.data.order)

        return {
            ...globalState,
            cart: null,
            lastOrder: new Entity(new Cart(action.data.order)),
            orders: orders,
        }

    case 'CartApi.Cart.getOrder.success':
        orders = { ...orders, ...globalState.orders }
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
