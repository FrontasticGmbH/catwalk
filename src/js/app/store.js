import { createStore, applyMiddleware } from 'redux'
import thunk from 'redux-thunk'

import Entity from './entity'
import reducer from './reducer'

let mountNode = document.getElementById('app')
let props = mountNode ? JSON.parse(mountNode.getAttribute('data-props')) : {}

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
    applyMiddleware(thunk)
)
