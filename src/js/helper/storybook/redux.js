import { createStore } from 'redux'
import { Provider } from 'react-redux'
import * as React from 'react'
import app from '../../app/app'

const featureFlags = {
    komplettPreis: true,
}

export const storybookStore = createStore(
    (state) => {
        return state
    },
    {
        wishlist: {
            wishlist: {
                isComplete: () => {
                    return null
                },
            },
        },
        cart: { cart: {} },
        app: {
            context: {
                locale: 'de_DE',
                currency: 'EUR',
                routes: {
                    'Apollo.CartBundle.order': {
                        _type: 'stdClass',
                        path: '/order/{orderId}/{token}',
                        requirements: {
                            _type: 'stdClass',
                        },
                    },
                    'Frontastic.WishlistApi.Wishlist.get': {
                        _type: 'stdClass',
                        path: '/api/cart/wishlist',
                        requirements: {
                            _type: 'stdClass',
                        },
                    },
                },
                session: {
                    loggedIn: false,
                },
                project: {
                    configuration: {
                        cloudinary: {
                            apiKey: '183832318368184',
                            apiSecret: '93R2n-_itjETBA3o1OSN0X-w56g',
                            cloudName: 'dlwdq84ig',
                        },
                        media: {
                            apiKey: '183832318368184',
                            apiSecret: '93R2n-_itjETBA3o1OSN0X-w56g',
                            cloudName: 'dlwdq84ig',
                            engine: 'cloudinary',
                        },
                    },
                    name: 'Apollo Storybook',
                },
                featureFlags: featureFlags,
                hasFeature: (feature) => {
                    return Boolean(featureFlags[feature])
                },
            },
            route: {
                get: () => {
                    return ''
                },
            },
        },
    }
)

// The router does not respect the redux store from the react context,
// so we need to give it the routes explicitly.
app.getRouter().setRoutes(storybookStore.getState().app.context.routes)

export const withFrontasticRedux = (storyFn) => {
    return <Provider store={storybookStore}>{storyFn()}</Provider>
}
