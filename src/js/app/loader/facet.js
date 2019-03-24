import Entity from '../entity'
import _ from 'lodash'

import { ConfigurationSchema, FacetTypeSchemaMap } from 'frontastic-common'

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

    this.loadFacets = (parameters) => {
        this.api.trigger('Frontastic.Frontend.Facet.all', parameters, 'facet')
    }
}

const initialGlobalState = {
    facets: null,
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    let facets = new Entity()

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            facets: Entity.purge(globalState.facets),
        }
    case 'Frontend.Facet.all.success':
        facets = new Entity(
            _.map(action.data, (facetConfig) => {
                let facetConfigNew = _.cloneDeep(facetConfig)
                facetConfigNew.facetOptions = new ConfigurationSchema(
                    (FacetTypeSchemaMap[facetConfig.attributeType] || {}).schema || [],
                    facetConfig.facetOptions
                )
                return facetConfigNew
            }),
            86400
        )

        return {
            ...globalState,
            facets: facets,
        }

    case 'Frontend.Facet.all.error':
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
