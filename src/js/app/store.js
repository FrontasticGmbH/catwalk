import { createStore, applyMiddleware, compose } from 'redux'
import thunk from 'redux-thunk'

import Entity from './entity'
import reducer from './reducer'

// Try to read initial props from DOM
let mountNode = null
let props = {}
if (typeof document !== 'undefined') {
    mountNode = document && document.getElementById('app')
    props = mountNode ? JSON.parse(mountNode.getAttribute('data-props')) : {}
}

// Use thunk if available (not available during SSR)
let composeEnhancers = compose
if (typeof window !== 'undefined') {
    /* eslint-disable no-underscore-dangle */
    composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
    /* eslint-enable */
}

export default createStore(
    reducer,
    {
        node: {
            error: null,
            trees: {},
            nodes: {},
            nodeData: {},
            pages: {},
            last: {
                node: new Entity(props.node),
                data: new Entity(props.data),
                page: new Entity(props.page),
            },
            currentNodeId: null,
            currentCacheKey: null,
        },
    },
    composeEnhancers(applyMiddleware(thunk))
)
