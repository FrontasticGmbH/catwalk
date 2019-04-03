import { combineReducers } from 'redux'

import Context from './context'
import ComponentInjector from './injector'

import DevLoader from './loader/dev'
import ContextLoader from './loader/context'
import NodeLoader from './loader/node'
import DataLoader from './loader/data'
import TasticLoader from './loader/tastic'
import FacetLoader from './loader/facet'
import CategoryLoader from './loader/category'
import CartLoader from './loader/cart'
import WishlistLoader from './loader/wishlist'

const initialGlobalState = {
    route: null,
    context: new Context(),
}

const reducer = (globalState = initialGlobalState, action) => {
    if ((typeof PRODUCTION === 'undefined') &&
        action.type &&
        (action.type.substr(0, 2) !== '@@') &&
        console.groupCollapsed &&
        window) {
        console.groupCollapsed('%c🔊 %c%s', 'color: gray', 'color: darkmagenta', action.type)
        console.info(action)
        console.groupEnd()
    }

    let context = null
    switch (action.type) {
    case 'FRONTASTIC_INIT':
        return {
            ...globalState,
        }
    case 'FRONTASTIC_ROUTE':
        return {
            ...globalState,
            route: action.route,
        }
    case 'ApiBundle.Api.context.success':
        context = new Context(action.data)

        return {
            ...globalState,
            context: context,
        }
    case 'ApiBundle.Api.context.error':
        context = new Context(globalState.context)
        context.session.message = action.data

        return {
            ...globalState,
            context: context,
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default combineReducers({
    app: reducer,
    dev: DevLoader.handleAction,
    user: ContextLoader.handleAction,
    node: NodeLoader.handleAction,
    data: DataLoader.handleAction,
    tastic: TasticLoader.handleAction,
    cart: CartLoader.handleAction,
    facet: FacetLoader.handleAction,
    category: CategoryLoader.handleAction,
    wishlist: WishlistLoader.handleAction,

    // Optional customer reducers
    ...ComponentInjector.getReducer()
})
