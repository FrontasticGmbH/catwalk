import _ from 'lodash'

import Entity from '../entity'
import UrlContext from '../urlContext'

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

    this.loadTree = (parameters) => {
        this.api.trigger('Frontastic.Frontend.Node.tree', parameters, parameters.root || 'root')
    }

    this.loadNode = (parameters) => {
        this.api.trigger('Frontastic.Frontend.Node.view', parameters, parameters.nodeId)
    }

    this.reloadPreview = (parameters) => {
        this.api.trigger('Frontastic.Frontend.Preview.view', parameters, parameters.preview)
    }

    this.loadMaster = (route, parameters) => {
        this.api.request(
            'GET',
            route,
            parameters,
            null,
            (data, parameters) => {
                this.store.dispatch({
                    id: data.node.nodeId,
                    cacheKey: UrlContext.getActionHash(parameters),
                    type: 'Frontend.Master.view.success',
                    data: data,
                    parameters: parameters,
                })
            },
            (error) => {
                this.store.dispatch({
                    id: null,
                    cacheKey: UrlContext.getActionHash(parameters),
                    type: 'Frontend.Master.view.error',
                    error: error,
                })
            }
        )
    }
}

const initialGlobalState = {
    error: null,
    trees: {},
    nodes: {},
    nodeData: {},
    pages: {},
    last: {
        node: null,
        data: null,
        page: null,
    },
    currentNodeId: null,
    currentCacheKey: null,
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    let trees = {}
    let nodes = {}
    let nodeData = {}
    let pages = {}

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        return {
            error: null,
            trees: Entity.purgeMap(globalState.trees),
            nodes: Entity.purgeMap(globalState.nodes),
            nodeData: Entity.purgeMap(globalState.nodeData),
            pages: Entity.purgeMap(globalState.pages),
            last: globalState.last,

            // Setting the following two to `null` means a visible page load.
            // We only want this if there is some major change, eg. if we need
            // data from the server or the display structure changed.
            currentNodeId: UrlContext.hasChanged(action.lastRoute, action.route) ? null : globalState.currentNodeId,
            currentCacheKey: UrlContext.hasChanged(action.lastRoute, action.route) ? null : globalState.currentCacheKey,
        }

    case 'Frontend.Node.initialize':
        return {
            ...globalState,
            last: {
                node: new Entity(action.data.node),
                page: new Entity(action.data.page),
                data: new Entity(action.data.data),
            },
        }

    case 'Frontend.Node.tree.success':
        trees = _.extend({}, globalState.trees)
        if (action.id) {
            trees[action.id] = new Entity(action.data, 3600)
        }

        return {
            ...globalState,
            trees: trees,
        }
    case 'Frontend.Node.tree.error':
        trees = _.extend({}, globalState.trees)
        if (action.id) {
            trees[action.id] = new Entity().setError(action.error)
        }

        return {
            ...globalState,
            trees: trees,
        }

    case 'Frontend.Node.view.success':
    case 'Frontend.Master.view.success':
    case 'Frontend.Preview.view.success':
    case 'Frontend.Master.Error.view.success':
        nodes = _.extend({}, globalState.nodes)
        nodeData = _.extend({}, globalState.nodeData)
        pages = _.extend({}, globalState.pages)
        if (action.id) {
            nodes[action.id] = new Entity(action.data.node, 3600)
            nodeData[action.cacheKey] = new Entity(action.data.data, 3600)
            pages[action.id] = new Entity(action.data.page, 3600)
        }

        let nextLoadingData = new Entity(action.data.data)
        nextLoadingData.loading = true

        return {
            ...globalState,
            currentNodeId: action.id,
            currentCacheKey: action.cacheKey,
            nodes: nodes,
            nodeData: nodeData,
            pages: pages,
            last: {
                node: new Entity(_.extend({}, action.data.node)),
                data: nextLoadingData,
                page: new Entity(_.extend({}, action.data.page)),
            },
        }
    case 'Frontend.Node.view.error':
    case 'Frontend.Master.view.error':
    case 'Frontend.Preview.view.error':
    case 'Frontend.Master.Error.view.error':
        nodes = _.extend({}, globalState.nodes)
        nodeData = _.extend({}, globalState.nodeData)
        pages = _.extend({}, globalState.pages)
        if (action.id) {
            nodes[action.id] = new Entity().setError(action.error)
            nodeData[action.cacheKey] = new Entity().setError(action.error)
            pages[action.id] = new Entity().setError(action.error)
        }

        return {
            ...globalState,
            currentNodeId: action.id,
            currentCacheKey: action.cacheKey,
            nodes: nodes,
            nodeData: nodeData,
            pages: pages,
        }

    case 'Frontend.Error.view.success':
        if (globalState.error) {
            // Do not update error if there is already an error page display.
            // This could cause an endless refresh cycle if a tastic on the
            // error page tries to fetch data and this errors again.
            //
            // The error flag is reset on every route change so that the error
            // page will be reloaded if the route had changed.
            return globalState
        }

        return {
            ...globalState,
            error: action.data,
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default Loader
