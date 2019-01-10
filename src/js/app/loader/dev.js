/**
 * Special context loader
 */
let Loader = function (store, api) {
    this.store = store
    this.api = api

    this.loadTunnel = (parameters) => {
        this.api.triggerContinuously('Frontastic.DevVmBundle.Ngrok.tunnels')
    }
}

const initialGlobalState = {
    tunnels: [],
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    switch (action.type) {
    case 'FRONTASTIC_CONTEXT_SWITCH':
        return initialGlobalState
    case 'FRONTASTIC_ROUTE':
        return globalState
    case 'DevVmBundle.Ngrok.tunnels.success':
        return {
            tunnels: action.data,
        }
    case 'DevVmBundle.Ngrok.tunnels.error':
        return {
            tunnels: [],
        }
    default:
        // Do nothing for all other actions
    }

    return globalState
}

export default Loader
