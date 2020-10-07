//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import app from '../../../app/app'
import Loading from '../../../app/loading'
import Message from '../../../app/message'

class AccountConfirmTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            confirm: null,
        }
    }

    componentDidMount () {
        if (!this.props.confirmationToken) {
            return
        }

        app.api.request(
            'POST',
            'Frontastic.AccountApi.Api.confirm',
            { confirmationToken: this.props.confirmationToken, ownErrorHandler: true },
            {},
            (json) => {
                app.getLoader('context').notifyUser(
                    <Message
                        code='account.message.confirmSuccess'
                        message='Account confirmation successfull.'
                    />,
                    'success'
                )
                app.getLoader('context').refresh()
                app.getRouter().replace('Frontastic.Frontend.Master.Account.profile')
            },
            (json) => {
                app.getLoader('context').notifyUser(
                    <Message
                        code='account.message.confirmError'
                        message='Could not confirm account'
                    />,
                    'error'
                )
                app.getRouter().history.replace('/')
            }
        )
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return !this.state.confirm ? <Loading /> : null
    }
}

AccountConfirmTastic.propTypes = {
    confirmationToken: PropTypes.string,
}

AccountConfirmTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            confirmationToken: globalState.app.route.get('confirmationToken', null),
        }
    }
)(AccountConfirmTastic)
