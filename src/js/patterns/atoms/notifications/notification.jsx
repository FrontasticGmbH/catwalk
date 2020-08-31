import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import AtomsButton from '../buttons/button'

import ComponentInjector from '../../../app/injector'

class AtomsNotification extends Component {
    componentDidMount = () => {
        if (this.props.autoHideDuration && this.props.onClose) {
            window.setTimeout(this.props.onClose, this.props.autoHideDuration)
        }
    }

    render () {
        return (<div className={classnames(
            'c-alert',
            'c-alert--' + this.props.type,
        )}>
            <p className='c-alert__message'>{this.props.message}</p>
            {this.props.onClose ? <AtomsButton onClick={() => { this.props.onClose() }}>x</AtomsButton> : null}
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

export default ComponentInjector.return('AtomsNotification', AtomsNotification)
