import { createStore } from 'redux'

import Entity from './entity'
import reducer from './reducer'

let mountNode = null
let props = {}
if (typeof document !== 'undefined') {
    mountNode = document.getElementById('app')
    props = mountNode ? JSON.parse(mountNode.getAttribute('data-props')) : {}
}

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
    }
)
