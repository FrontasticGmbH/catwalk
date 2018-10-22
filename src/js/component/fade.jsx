import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Transition } from 'react-transition-group'

import ComponentInjector from '../app/injector'

class Fade extends Component {
    render () {
        return (<Transition appear in={this.props.in} timeout={115}>
            {(state) => {
                return (<div className={'u-fade u-fade--' + state}>
                    {this.props.children}
                </div>)
            }}
        </Transition>)
    }
}

Fade.propTypes = {
    in: PropTypes.bool.isRequired,
    children: PropTypes.any.isRequired,
}

Fade.defaultProps = {
}

export default ComponentInjector.return('Fade', Fade)
