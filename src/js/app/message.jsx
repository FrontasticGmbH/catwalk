import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { FormattedMessage } from 'react-intl'

class Message extends Component {
    render() {
        if (!this.props.code) {
            console.warn(`FormattedMessage is missing an id, and will thus not be translated: ${this.props.message}`)
            return this.props.message
        }
        return (
            <FormattedMessage id={this.props.code} defaultMessage={this.props.message} values={this.props.parameters} />
        )
    }
}

Message.propTypes = {
    message: PropTypes.string.isRequired,
    code: PropTypes.string.isRequired,
    parameters: PropTypes.object,
}

Message.defaultProps = {
    code: 'Unknown',
    parameters: {},
}

export default Message
