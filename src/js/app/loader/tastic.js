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

    this.loadTastics = (parameters) => {
        this.api.trigger('Frontastic.Frontend.Tastic.all', parameters, 'tastic')
    }
}

const initialGlobalState = {
    tastics: null,
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            tastics: Entity.purge(globalState.tastics),
        }

    case 'Frontend.Tastic.initialize':
    case 'Frontend.Tastic.all.success':
        return {
            ...globalState,
            tastics: new Entity(action.data, 86400),
        }

    case 'Frontend.Tastic.all.error':
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
