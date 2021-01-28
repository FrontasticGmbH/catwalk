import { createStore, applyMiddleware, compose } from 'redux'

import thunk from 'redux-thunk'

import Entity from './entity'
import createReducer from './reducer'
import UrlContext from './urlContext'

import { ConfigurationSchema, FacetTypeSchemaMap } from 'frontastic-common'
import ComponentInjector from './injector'

// Try to read initial props from DOM
let dataNode = null
let props = {
    route: {},
    node: {
        nodeId: null,
    },
}
if (typeof document !== 'undefined') {
    dataNode = document && document.getElementById('appData')

    if (dataNode) {
        const newProps = JSON.parse(dataNode.getAttribute('data-props'))
        if (newProps) {
            // Within storybook, we do have a document and mountnode, but no
            // data-props attribute.
            props = { ...props, ...newProps }
        }
    }
}

// Use thunk if available (not available during SSR)
let composeEnhancers = compose
if (typeof window !== 'undefined') {
    let reduxDevtoolsOptions = {}
    const urlParams = new URLSearchParams(window.location.search)
    const optimizeReduxDevtools = urlParams.get('_frontastic_disable_redux_devtools_optimization') === null
    const stripString = 'Stripped from redux-dev-tools for performance reasons. You can temporarily disable this by adding ?_frontastic_disable_redux_devtools_optimization to your URL'

    if (optimizeReduxDevtools) {
        reduxDevtoolsOptions = {
            actionSanitizer: (action) => {
                if (action.type !== 'Frontend.App.initialize') {
                    return action
                }

                return {
                    ...action,
                    data: {
                        ...action.data,
                        // This contains our loader, which contains the api and all loaders, which all contain the api.
                        // BIG object, and I think not useful to look at in the redux devtools most of the time.
                        loader: stripString,
                    },
                }
            },
            stateSanitizer: (state) => {
                return {
                    ...state,
                    app: {
                        ...state.app,
                        context: {
                            ...state.app.context,
                            // The routes tend do be a rather large collection that is rarely needed in debugging.
                            routes: stripString,
                        },
                    },
                }
            },
        }
    }

    /* eslint-disable no-underscore-dangle */
    if (window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__) {
        composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__(reduxDevtoolsOptions)
    }
    /* eslint-enable */
}

let cacheKey = UrlContext.getActionHash(props.route)

export default () => {
    const combinedMiddlewares = [thunk, ...ComponentInjector.getMiddlewares()]

    return createStore(
        createReducer(),
        {
            node: {
                error: null,
                trees: {},
                nodes: {
                    [props.node.nodeId]: new Entity(props.node),
                },
                nodeData: {
                    [cacheKey]: new Entity(props.data),
                },
                pages: {
                    [props.node.nodeId]: new Entity(props.page),
                },
                last: {},
                currentNodeId: props.node.nodeId,
                currentCacheKey: cacheKey,
            },
            tastic: {
                tastics: new Entity(props.tastics, 86400),
            },
            facet: {
                facets: new Entity(
                    // @TODO: Clone from app/loader/facet.js – extract!
                    (props.facets || []).map((facetConfig) => {
                        let facetConfigNew = JSON.parse(JSON.stringify(facetConfig))
                        facetConfigNew.facetOptions = new ConfigurationSchema(
                            (FacetTypeSchemaMap[facetConfig.attributeType] || {}).schema || [],
                            facetConfig.facetOptions
                        )
                        return facetConfigNew
                    }),
                    86400
                ),
            },
            category: {
                categories: new Entity(props.categories, 86400),
            },
        },
        composeEnhancers(applyMiddleware(...combinedMiddlewares))
    )
}
