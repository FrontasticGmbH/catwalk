import React, {Component} from 'react';
import PropTypes from 'prop-types'
import {FormattedMessage} from 'react-intl';

class Message extends Component {
    render() {
        return (
            <FormattedMessage
                id={this.props.code}
                defaultMessage={this.props.message}
                values={this.props.parameters}
            />
        );
    }
}

Message.propTypes = {
    message: PropTypes.string.isRequired,
    code: PropTypes.string,
    parameters: PropTypes.object,
}

Message.defaultProps = {
    code: 'Unknown',
    parameters: {},
}

export default Message
