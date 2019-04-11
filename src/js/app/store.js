import { createStore, applyMiddleware, compose } from 'redux'
import thunk from 'redux-thunk'
import _ from 'lodash'

import Entity from './entity'
import reducer from './reducer'
import UrlContext from './urlContext'

import { ConfigurationSchema, FacetTypeSchemaMap } from 'frontastic-common'

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

let cacheKey = UrlContext.getActionHash(props.route)

export default createStore(
    reducer,
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
                _.map(props.facets, (facetConfig) => {
                    let facetConfigNew = _.cloneDeep(facetConfig)
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
    composeEnhancers(applyMiddleware(thunk))
)
