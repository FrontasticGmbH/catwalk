import React, {Component} from 'react';
import PropTypes from 'prop-types'
import {FormattedMessage} from 'react-intl';

class Error extends Component {
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

Error.propTypes = {
    message: PropTypes.string.isRequired,
    code: PropTypes.string,
    parameters: PropTypes.object,
}

Error.defaultProps = {
    code: 'Unknown',
    parameters: {},
}

export default Error
