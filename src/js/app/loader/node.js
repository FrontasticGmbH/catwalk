import Entity from '../entity'
import UrlContext from '../urlContext'

import withRetries from '../withRetries'
import extractErrors from './node/errorExtractor'

export const trackingPageView = ({ nodeId, data, isMasterPage }) => {
    return {
        type: 'Frontend.Tracking.PageView',
        nodeId,
        data,
        isMasterPage,
    }
}

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

    /**
     * @param parameters
     * @return Promise
     */
    this.loadTree = (parameters) => {
        return this.api.trigger('Frontastic.Frontend.Node.tree', parameters, parameters.root || 'root')
    }

    /**
     * @param parameters
     * @return Promise
     */
    this.loadNode = (parameters) => {
        return new Promise((resolve, reject) => {
            withRetries(
                (retry, tryCount) => {
                    this.api.request(
                        'GET',
                        'Frontastic.Frontend.Node.view',
                        parameters,
                        null,
                        (data, parameters) => {
                            const isRetry = tryCount < 2
                            this.store.dispatch({
                                type: 'Frontend.Node.view.success',
                                id: parameters.nodeId,
                                cacheKey: UrlContext.getActionHash(parameters),
                                data: data,
                                parameters: parameters,
                                isRetry,
                            })

                            const nodeDataErrors = extractErrors(data.data)
                            if (nodeDataErrors.length > 0) {
                                // eslint-disable-next-line no-console
                                console.info('Errors in node data, attempting to retry', nodeDataErrors)
                                setTimeout(retry, 100)
                            }
                            if (!isRetry) {
                                this.store.dispatch(trackingPageView({
                                    nodeId: parameters.nodeId,
                                    data: data,
                                    isMasterPage: false,
                                }))
                            }

                            resolve()
                        },
                        (error) => {
                            this.store.dispatch({
                                type: 'Frontend.Node.view.success.error',
                                id: parameters.nodeId,
                                cacheKey: UrlContext.getActionHash(parameters),
                                error: error,
                            })

                            reject(error)
                        }
                    )
                },
                2,
                () => {
                    // eslint-disable-next-line no-console
                    console.error('Giving up retrying to fetch data for node', parameters)
                }
            )
        })
    }

    /**
     * @param parameters
     * @return Promise
     */
    this.reloadPreview = (parameters) => {
        return this.api.trigger('Frontastic.Frontend.Preview.view', parameters, parameters.preview)
    }

    /**
     * @param route
     * @param parameters
     * @return Promise
     */
    this.loadMaster = (route, parameters) => {
        return new Promise((resolve, reject) => {
            this.api.request(
                'GET',
                route,
                parameters,
                null,
                (data, parameters) => {
                    this.store.dispatch({
                        id: data.node.nodeId,
                        cacheKey: UrlContext.getActionHash({ route, parameters }),
                        type: 'Frontend.Master.view.success',
                        data: data,
                        parameters: parameters,
                    })
                    this.store.dispatch(trackingPageView({
                        nodeId: data.node.nodeId,
                        isMasterPage: true,
                        data,
                    }))

                    resolve()
                },
                (error) => {
                    this.store.dispatch({
                        id: null,
                        cacheKey: UrlContext.getActionHash(parameters),
                        type: 'Frontend.Master.view.error',
                        error: error,
                    })

                    reject(error)
                }
            )
        })
    }
}

const initialGlobalState = {
    loading: false,
    error: null,
    trees: {},
    nodes: {},
    nodeData: {},
    nodeIds: {},
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
    let nodeIds = {}
    let pages = {}

    switch (action.type) {
    case 'FRONTASTIC_ROUTE':
        const currentCacheKey = UrlContext.getActionHash(action.route)

        // Assume that the currentNode does not change.
        // This is particularly important for the first render cycle,
        // because it will (otherwise) we initialized to late and we will
        // get an intermitting empty page
        let currentNodeId = globalState.currentNodeId

        if (action.lastRoute && action.lastRoute.route !== action.route.route) {
            // We are apparently changing the node, so check if we have the node ID cached
            if (globalState.nodeIds[currentCacheKey]?.isComplete && globalState.nodeIds[currentCacheKey].isComplete()) {
                currentNodeId = globalState.nodeIds[currentCacheKey].data?.nodeId ?? null
            } else {
                currentNodeId = null
            }
        }

        return {
            loading: true,
            error: null,
            trees: Entity.purgeMap(globalState.trees),
            nodes: Entity.purgeMap(globalState.nodes),
            nodeData: Entity.purgeMap(globalState.nodeData),
            nodeIds: Entity.purgeMap(globalState.nodeIds),
            pages: Entity.purgeMap(globalState.pages),
            last: globalState.last,
            currentCacheKey,
            currentNodeId,
        }

    case 'Frontend.Node.initialize':
        const node = new Entity(action.data.node, 3600)
        const page = new Entity(action.data.page, 3600)
        const data = new Entity(action.data.data, 3600)
        let nodeId = new Entity()
        if (action.data.node.nodeId ?? null) {
            nodeId = new Entity({ nodeId: action.data.node.nodeId }, 3600)
        }

        return {
            ...globalState,
            loading: !action.data.node,
            currentNodeId: action.data.node.nodeId,
            previewId: action.data.route.parameters.preview,
            nodes: {
                [action.data.node.nodeId || action.data.route.parameters.preview]: node,
            },
            pages: {
                [action.data.node.nodeId || action.data.route.parameters.preview]: page,
            },
            nodeData: {
                [globalState.currentCacheKey]: data,
            },
            nodeIds: {
                [globalState.currentCacheKey]: nodeId,
            },
            last: {
                node: node,
                page: page,
                data: data,
            },
        }

    case 'Frontend.Node.tree.success':
        trees = { ...globalState.trees }
        if (action.id) {
            trees[action.id] = new Entity(action.data, 3600)
        }

        return {
            ...globalState,
            trees: trees,
        }
    case 'Frontend.Node.tree.error':
        trees = { ...globalState.trees }
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
        nodes = { ...globalState.nodes }
        nodeData = { ...globalState.nodeData }
        nodeIds = { ...globalState.nodeIds }
        pages = { ...globalState.pages }
        if (action.id) {
            nodes[action.id] = new Entity(action.data.node, 3600)
            nodeData[globalState.currentCacheKey] = new Entity(action.data.data, 3600)
            nodeIds[globalState.currentCacheKey] = new Entity({ nodeId: action.data.node.nodeId }, 3600)
            pages[action.id] = new Entity(action.data.page, 3600)
        }

        let nextLoadingData = new Entity(action.data.data)
        nextLoadingData.loading = true

        return {
            ...globalState,
            loading: false,
            currentNodeId: action.id,
            nodes: nodes,
            nodeData: nodeData,
            nodeIds: nodeIds,
            pages: pages,
            last: {
                node: new Entity({ ...action.data.node }),
                data: nextLoadingData,
                page: new Entity({ ...action.data.page }),
            },
        }
    case 'Frontend.Node.view.error':
    case 'Frontend.Master.view.error':
    case 'Frontend.Preview.view.error':
    case 'Frontend.Master.Error.view.error':
        nodes = { ...globalState.nodes }
        nodeData = { ...globalState.nodeData }
        nodeIds = { ...globalState.nodeIds }
        pages = { ...globalState.pages }
        if (action.id) {
            nodes[action.id] = new Entity().setError(action.error)
            nodeData[action.cacheKey] = new Entity().setError(action.error)
            nodeIds[action.cacheKey] = new Entity().setError(action.error)
            pages[action.id] = new Entity().setError(action.error)
        }

        return {
            ...globalState,
            loading: false,
            currentNodeId: action.id,
            currentCacheKey: 'error',
            nodes: nodes,
            nodeData: nodeData,
            nodeIds: nodeIds,
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
            loading: false,
            error: action.data,
        }

    default:
        // Do nothing for other actions
    }

    return globalState
}

export default Loader
