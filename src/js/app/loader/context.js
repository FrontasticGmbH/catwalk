import React from 'react'

import { generateId } from 'frontastic-common'

import app from '../app'
import Context from '../context'
import Entity from '../entity'
import Message from '../message'

/**
 * Special context loader
 */
let Loader = function (store, api) {
    this.store = store
    this.api = api

    /**
     * @param parameters
     * @return Promise
     */
    this.refresh = (parameters) => {
        return this.api.request(
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

                // Restart continuous updates
                app.api.clearContinuousRequests()
            },
            (error) => {
                this.store.dispatch({
                    type: 'ApiBundle.Api.context.error',
                    data: error,
                })
            }
        )
    }

    this.register = (user, redirect = true) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.register',
            { ownErrorHandler: true },
            user,
            (json) => {
                this.notifyUser(
                    <Message
                        code='account.message.registered'
                        message='Registration successfull – we sent you an email to confirm your registration.'
                    />,
                    'success'
                )
                this.store.dispatch({
                    type: 'Frontastic.AccountApi.Api.register.success',
                    data: json,
                })

                if (redirect) {
                    app.getRouter().replace('Frontastic.Frontend.Master.Account.profile')
                }
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.login = (email, password, previous = null) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.login',
            { ownErrorHandler: true },
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
                // @TODO: We should ensure Symfony auth errors contain sensible codes
                if (json.message === 'Unauthenticated: Your email address was not yet verified.') {
                    this.notifyUser(<Message code='symfony.notVerified' {...json} />, 'error')
                } else {
                    this.notifyUser(<Message code='symfony.invalid' {...json} />, 'error')
                }
            }
        )
    }

    this.logout = () => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.logout',
            null,
            null,
            (json) => {
                this.notifyUser(<Message code='account.message.logout' message='Successfully logged out' />, 'success')
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

    this.addAddress = (address) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.addAddress',
            { ownErrorHandler: true },
            address,
            (json) => {
                this.refresh()
                this.notifyUser(<Message code='account.message.addressNew' message='Added new address' />, 'success')

                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.updateAddress = (address) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.updateAddress',
            { ownErrorHandler: true },
            address,
            (json) => {
                this.refresh()
                this.notifyUser(<Message code='account.message.addressUpdated' message='Updated address' />, 'success')

                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.removeAddress = (address) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.removeAddress',
            { ownErrorHandler: true },
            address,
            (json) => {
                this.refresh()
                this.notifyUser(<Message code='account.message.addressRemoved' message='Removed address' />, 'success')

                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.setDefaultBillingAddress = (address) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.setDefaultBillingAddress',
            { ownErrorHandler: true },
            address,
            (json) => {
                this.notifyUser(<Message code='account.message.billingDefault' message='Set new default billing address' />, 'success')

                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.setDefaultShippingAddress = (address) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.setDefaultShippingAddress',
            { ownErrorHandler: true },
            address,
            (json) => {
                this.notifyUser(<Message code='account.message.shippingDefault' message='Set new default shipping address' />, 'success')

                let route = this.store.getState().app.route
                app.getLoader('node').loadMaster(route.route, route.parameters)
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.updateUser = (user) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.update',
            { ownErrorHandler: true },
            user,
            (json) => {
                this.refresh()
                this.notifyUser(<Message code='account.message.update' message='Account data updated' />, 'success')

                this.store.dispatch({
                    type: 'AccountApi.Api.get.success',
                    data: json,
                    id: user.email,
                })
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.requestPasswordReset = (email) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.requestReset',
            { ownErrorHandler: true },
            { email: email },
            (json) => {
                this.notifyUser(<Message code='account.message.reset' message='Password reset mail sent.' />, 'success')
            },
            (json) => {
                this.notifyUser(<Message code='account.message.resetFail' message={'Could not find account with email ' + email} />, 'error')
            }
        )
    }

    this.resetPassword = (token, newPassword) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.reset',
            { ownErrorHandler: true, token: token },
            { newPassword: newPassword },
            (json) => {
                this.notifyUser(<Message code='account.message.passwordUpdate' message='Password updated' />, 'success')

                app.getRouter().replace('Frontastic.Frontend.Master.Account.profile')
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.updatePassword = (oldPassword, newPassword) => {
        return this.api.request(
            'POST',
            'Frontastic.AccountApi.Api.changePassword',
            { ownErrorHandler: true },
            {
                oldPassword: oldPassword,
                newPassword: newPassword,
            },
            (json) => {
                this.refresh()
                this.notifyUser(<Message code='account.message.passwordUpdate' message='Password updated' />, 'success')
            },
            (json) => {
                this.notifyUser(<Message {...json} />, 'error')
            }
        )
    }

    this.notifyUser = (message, type = 'info', timeout = 5000) => {
        if (message && message.props && (message.props.code === 'commercetools.ConcurrentModification')) {
            return null
        }

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
        users = { ...globalState.users }
        if (action.id) {
            users[action.id] = new Entity(action.data, 3600)
        }

        return {
            ...globalState,
            users: users,
        }
    case 'AccountApi.Api.get.error':
        users = { ...globalState.users }
        if (action.id) {
            users[action.id] = new Entity().setError(action.error)
        }

        return {
            ...globalState,
            users: users,
        }

    case 'Frontastic.Notification.add':
        notifications = { ...globalState.notifications }
        notifications[action.id] = action.data

        return {
            ...globalState,
            notifications: notifications,
        }
    case 'Frontastic.Notification.remove':
        notifications = { ...globalState.notifications }
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
