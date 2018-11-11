import { generateId } from 'frontastic-common'

import app from '../app'
import Context from '../context'
import Entity from '../entity'

/**
 * Special context loader
 */
let Loader = function (store, api) {
    this.store = store
    this.api = api

    this.refresh = (parameters) => {
        this.api.request(
            'GET',
            'Frontastic.ApiCoreBundle.Api.context',
            parameters,
            null,
            (data) => {
                let context = new Context(data)

                app.getRouter().setContext(context)
                app.getRouter().setRoutes(context.routes)

                this.store.dispatch({
                    type: 'ApiBundle.Api.context.success',
                    data: data,
                })
                app.loadForLocation(window.location)
            },
            (error) => {
                this.store.dispatch({
                    type: 'ApiBundle.Api.context.error',
                    data: error,
                })
            }
        )
    }

    this.register = (user, previous = null) => {
        this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.register',
            { ownErrorHandler: true, },
            user,
            (json) => {
                this.notifyUser('Registration successfull')
                this.refresh()

                if (previous) {
                    app.getRouter().replace(
                        previous.route,
                        previous.parameters
                    )
                }
            },
            (json) => {
                this.notifyUser('Could not register: ' + json.message)
            }
        )
    }

    this.login = (email, password, previous = null) => {
        this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.login',
            { ownErrorHandler: true, },
            { email: email, password: password },
            (json) => {
                this.refresh()

                if (previous) {
                    app.getRouter().replace(
                        previous.route,
                        previous.parameters
                    )
                }
            },
            (json) => {
                this.notifyUser('Login Failure – email or password are wrong.')
            }
        )
    }

    this.logout = () => {
        this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.logout',
            null,
            null,
            (json) => {
                this.refresh()
            },
            (json) => {
                this.store.dispatch({
                    type: 'ApiBundle.Api.context.error',
                    data: json.message,
                })
            }
        )
    }

    this.loadUser = (email) => {
        email = email || this.store.getState().app.context.session.user.email
        this.api.trigger('Frontastic.AccountApi.Api.get', { email: email }, email)
    }

    this.updateUser = (user) => {
        this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.update',
            null,
            user,
            (json) => {
                this.refresh()
                this.store.dispatch({
                    type: 'AccountApi.Api.get.success',
                    data: json,
                    id: user.email,
                })
            },
            (json) => {
                this.store.dispatch({
                    type: 'AccountApi.Api.get.error',
                    data: json,
                })
            }
        )
    }

    this.notifyUser = (message, type = 'info', timeout = null) => {
        let notificationId = generateId()
        this.store.dispatch({
            type: 'Frontastic.Notification.add',
            data: {
                notificationId: notificationId,
                message: message,
                type: type,
                timeout: timeout,
            },
            id: notificationId,
        })
        return notificationId
    }
}

const initialGlobalState = {
    users: {},
    notifications: {},
}

Loader.handleAction = (globalState = initialGlobalState, action) => {
    let users = {}
    let notifications = {}

    switch (action.type) {
    case 'FRONTASTIC_CONTEXT_SWITCH':
        return initialGlobalState
    case 'FRONTASTIC_ROUTE':
        return {
            users: Entity.purgeMap(globalState.users),
            // Never remove notifications ourselves
            notifications: globalState.notifications,
        }
    case 'AccountApi.Api.get.success':
        users = _.extend({}, globalState.users)
        if (action.id) {
            users[action.id] = new Entity(action.data, 3600)
        }

        return {
            ...globalState,
            users: users,
        }
    case 'AccountApi.Api.get.error':
        users = _.extend({}, globalState.users)
        if (action.id) {
            users[action.id] = new Entity().setError(action.error)
        }

        return {
            ...globalState,
            users: users,
        }

    case 'Frontastic.Notification.add':
        notifications = _.extend({}, globalState.notifications)
        notifications[action.id] = action.data

        return {
            ...globalState,
            notifications: notifications,
        }
    case 'Frontastic.Notification.remove':
        notifications = _.extend({}, globalState.notifications)
        delete notifications[action.id]

        return {
            ...globalState,
            notifications: notifications,
        }
    default:
        // Do nothing for all other actions
    }

    return globalState
}

export default Loader
