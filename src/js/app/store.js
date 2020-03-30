import { createStore, applyMiddleware, compose } from 'redux'

const logger = createLogger({
    // ...options
})

import thunk from 'redux-thunk'
import _ from 'lodash'

import Entity from './entity'
import createReducer from './reducer'
import UrlContext from './urlContext'

import { ConfigurationSchema, FacetTypeSchemaMap } from 'frontastic-common'
import ComponentInjector from './injector';

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
    /* eslint-disable no-underscore-dangle */
    composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
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
    },
    composeEnhancers(applyMiddleware(...combinedMiddlewares))
)
