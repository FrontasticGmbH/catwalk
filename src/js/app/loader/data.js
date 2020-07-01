import Entity from '../entity'

import _ from 'lodash'

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

    this.find = (parameters) => {
        return this.api.trigger('Frontastic.ApiCoreBundle.App.data', parameters, JSON.stringify(parameters))
    }

    this.get = (parameters) => {
        return this.api.trigger('Frontastic.ApiCoreBundle.App.get', parameters, parameters.dataId)
    }
}

const initialGlobalState = {
    data: {},
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    let data = {}

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            data: Entity.purgeMap(globalState.data),
        }
    case 'ApiCoreBundle.App.get.success':
    case 'ApiCoreBundle.App.data.success':
        data = _.extend({}, globalState.data)
        data[action.id] = new Entity(action.data, 3600)

        return {
            ...globalState,
            data: data,
        }
    case 'ApiCoreBundle.App.get.error':
    case 'ApiCoreBundle.App.data.error':
        data = _.extend({}, globalState.data)
        data[action.id] = new Entity().setError(action.error)

        return {
            ...globalState,
            data: data,
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default Loader
