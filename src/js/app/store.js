import { createStore, applyMiddleware, compose } from 'redux'
import thunk from 'redux-thunk'

import Entity from './entity'
import reducer from './reducer'

let mountNode = document.getElementById('app')
let props = mountNode ? JSON.parse(mountNode.getAttribute('data-props')) : {}

/* eslint-disable no-underscore-dangle */
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
/* eslint-enable */

export default createStore(
    reducer,
    {
        node: {
            last: {
                node: new Entity(props.node),
                data: new Entity(props.data),
                page: new Entity(props.page),
            },
        },
    },
    composeEnhancers(applyMiddleware(thunk))
)
