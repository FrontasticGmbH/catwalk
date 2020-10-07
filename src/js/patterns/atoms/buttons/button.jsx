//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import omit from '@frontastic/common/src/js/helper/omit'
import ComponentInjector from '../../../app/injector'

class AtomsButton extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        const Component = this.props.component

        return (<Component
            {...omit(this.props, ['children', 'component', 'className', 'type', 'ghost', 'outline', 'rounded', 'full', 'htmlType'])}
            type={this.props.htmlType}
            className={classnames(
                'c-button',
                this.props.className,
                (this.props.type ? 'c-button--' + this.props.type : null),
                (this.props.size ? 'c-button--' + this.props.size : null),
                {
                    'c-button--ghost': this.props.ghost,
                    'c-button--outline': this.props.outline,
                    'c-button--rounded': this.props.rounded,
                    'c-button--full': this.props.full,
                }
            )}>
            {this.props.children}
        </Component>)
    }
}

AtomsButton.propTypes = {
    children: PropTypes.node.isRequired,
    component: PropTypes.oneOfType([PropTypes.string, PropTypes.func]),
    className: PropTypes.string,
    type: PropTypes.oneOf([null, 'primary', 'secondary']),
    size: PropTypes.oneOf([null, 'small', 'large']),
    htmlType: PropTypes.string,
    ghost: PropTypes.bool,
    outline: PropTypes.bool,
    rounded: PropTypes.bool,
    full: PropTypes.bool,
}

AtomsButton.defaultProps = {
    component: 'button',
    className: '',
    type: null,
    htmlType: null,
    ghost: false,
    outline: false,
    rounded: false,
    full: false,
}

export default ComponentInjector.return('AtomsButton', AtomsButton)
