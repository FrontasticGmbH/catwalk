import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import app from '../../../app/app'
import Loading from '../../../app/loading'
import Markdown from '../../../component/markdown'

class AccountConfirmTastic extends Component {
    constructor(props) {
        super(props)

        this.state = {
            confirm: null,
        }
    }

    componentDidMount () {
        if (!this.props.token) {
            return
        }

        app.api.request(
            'POST',
            'Frontastic.AccountApi.Api.confirm',
            { token: this.props.token },
            {},
            (json) => {
                this.setState({ confirm: 'success' })
                app.getLoader('context').refresh()
            },
            (json) => {
                this.setState({ confirm: 'failure' })
            }
        )
    }

    render () {
        return (<Fragment>
            {!this.state.confirm ? <Loading /> : null}
            {this.state.confirm === 'success' ? <Markdown text={this.props.tastic.schema.get('success')} /> : null}
            {this.state.confirm === 'failure' ? <Markdown text={this.props.tastic.schema.get('failure')} /> : null}
        </Fragment>)
    }
}

AccountConfirmTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
    token: PropTypes.string,
}

AccountConfirmTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            token: globalState.app.route.get('token', null),
        }
    }
)(AccountConfirmTastic)
