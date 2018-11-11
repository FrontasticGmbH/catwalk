import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import _ from 'lodash'

import ComponentInjector from '../../../app/injector'

class AtomsNotification extends Component {
    render () {
        // @TODO: Handle autoHideDuration (call onClose after timeout)
        // @TODO: Add close button if onClose is defined
        return (<div className={classnames(
            'c-alert',
            'c-alert--' + this.props.type,
        )}>
            <p className='c-alert__message'>{this.props.message}</p>
        </div>)
    }
}

AtomsNotification.propTypes = {
    message: PropTypes.string.isRequired,
    type: PropTypes.oneOf(['success', 'info', 'warning', 'error']),
    onClose: PropTypes.func,
    autoHideDuration: PropTypes.number,
}

AtomsNotification.defaultProps = {
    type: 'info',
    onClose: null,
    autoHideDuration: null,
}

// These are just default props for the pattern library
AtomsNotification.testProps = {
    message: 'Notification!',
}

export default ComponentInjector.return('AtomsNotification', AtomsNotification)
