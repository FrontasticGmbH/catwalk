import Entity from '../entity'

/**
 * Loader classes like this consolidate all loading monitors for a domain
 * concept.
 *
 * They define one (or multiple) load() methods which execute an AJAX call
 * through the Api. You may implement parameter remapping and cache checks in
 * here. For the loaded date there should be a dispatched action through Redux.
 *
 * The Loader also defines a (static) method which handles its own actions and
 * applies the corresponding changes to the global store state.
 */
let Loader = function (store, api) {
    this.store = store
    this.api = api

    this.loadCategories = (parameters) => {
        this.api.trigger('Frontastic.Frontend.Category.all', parameters, 'category')
    }
}

const initialGlobalState = {
    categories: null,
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    let categories = new Entity()

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            categories: Entity.purge(globalState.categories),
        }
    case 'Frontend.Category.all.success':
        categories = new Entity(action.data.categories, 86400)

        return {
            ...globalState,
            categories: categories,
        }

    case 'Frontend.Category.all.error':
        // TODO: Handle error
        return {
            ...globalState,
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default Loader
