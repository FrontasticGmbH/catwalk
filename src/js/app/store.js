import { createStore, applyMiddleware, compose } from 'redux'
import thunk from 'redux-thunk'
import _ from 'lodash'

import Entity from './entity'
import reducer from './reducer'
import UrlContext from './urlContext'

import { ConfigurationSchema, FacetTypeSchemaMap } from 'frontastic-common'

// Try to read initial props from DOM
let dataNode = null
let props = {
    route: {},
    node: {
        nodeId: null,
    },
}

// Use thunk if available (not available during SSR)
let composeEnhancers = compose
if (typeof window !== 'undefined') {
    /* eslint-disable no-underscore-dangle */
    composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
    /* eslint-enable */
}

let cacheKey = UrlContext.getActionHash(props.route)

export default createStore(
    reducer,
    {
        node: {
            error: null,
            trees: {},
            nodes: {},
            nodeData: {},
            pages: {},
            last: {},
            currentNodeId: props.node.nodeId,
            currentCacheKey: cacheKey,
        },
        tastic: {
            tastics: new Entity([], 86400),
        },
        facet: {
            facets: new Entity([], 86400),
        },
        category: {
            categories: new Entity([], 86400),
        },
    },
    composeEnhancers(applyMiddleware(thunk))
)
