import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import _ from 'lodash'

import ComponentInjector from '../../../app/injector'

class AtomsHeading extends Component {
    typeMap = {
        alpha: 'h1',
        beta: 'h2',
        gamma: 'h3',
        delta: 'h4',
        epsilon: 'h5',
        zeta: 'h6',
    }

    render () {
        const Component = this.props.component || this.typeMap[this.props.type]

        return (<Component
            {..._.omit(this.props, ['children', 'component', 'className', 'type'])}
            className={classnames(
                'c-heading-' + this.props.type
            )}>
            {this.props.children}
        </Component>)
    }
}

AtomsHeading.propTypes = {
    component: PropTypes.string,
    type: PropTypes.oneOf(['alpha', 'beta', 'gamma', 'delta', 'epsilon', 'zeta']).isRequired,
    children: PropTypes.node.isRequired,
    className: PropTypes.string,
}

AtomsHeading.defaultProps = {
    component: null,
    className: '',
}

// These are just default props for the pattern library
AtomsHeading.testProps = {
    children: 'Heading Example',
    type: 'alpha',
}

export default ComponentInjector.return('AtomsHeading', AtomsHeading)
