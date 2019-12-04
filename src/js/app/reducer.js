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
import renderContextReducer from './reducer/renderContext'

const initialGlobalState = {
    route: null,
    context: new Context(),
    renderContext: {
        isServerSideRendering: false,
        deviceType: 'mobile',
    },
}

const reducer = (globalState = initialGlobalState, action) => {
    if ((typeof PRODUCTION === 'undefined' || !PRODUCTION) && // eslint-disable-line no-undef
        action.type &&
        (action.type.substr(0, 2) !== '@@') &&
        // eslint-disable-next-line no-console
        console.groupCollapsed &&
        typeof window !== 'undefined' &&
        window &&
        window.document) {
        // eslint-disable-next-line no-console
        console.groupCollapsed('%c🔊 %c%s', 'color: gray', 'color: darkmagenta', action.type)
        // eslint-disable-next-line no-console
        console.info(action)
        // eslint-disable-next-line no-console
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
    renderContext: renderContextReducer,

    // Optional customer reducers
    ...ComponentInjector.getReducer(),
})
