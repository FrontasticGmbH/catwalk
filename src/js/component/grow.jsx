import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Transition } from 'react-transition-group'

import ComponentInjector from '../app/injector'

class Grow extends Component {
    render () {
        return (<Transition appear in={this.props.in} timeout={225}>
            {(state) => {
                return (<div className={'u-grow u-grow--' + state}>
                    {this.props.children}
                </div>)
            }}
        </Transition>)
    }
}

Grow.propTypes = {
    in: PropTypes.bool.isRequired,
    children: PropTypes.any.isRequired,
}

Grow.defaultProps = {
}

export default ComponentInjector.return('Grow', Grow)
