import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

import icons from '../../../../icons/icomoon_icons/symbol-defs.svg'

class AtomsIcon extends Component {
    render () {
        return (<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xlinkHref='http://www.w3.org/1999/xlink'
            className={classnames(
                'o-icon',
                this.props.valign ? 'o-icon--' + this.props.valign : null,
                this.props.className
            )}
            data-icon={this.props.icon}
            data-icon-size={this.props.iconSize}
            aria-labelledby={this.props.iconTitle ? 'svg-title-' + this.props.icon : null}
            role={this.props.iconRole}>
            {this.props.iconTitle ? <title id={'svg-title-' + this.props.icon}>{this.props.iconTitle}</title> : null}
            <use xlinkHref={icons + '#' + this.props.iconPrefix + this.props.icon} />
        </svg>)
    }
}

AtomsIcon.propTypes = {
    icon: PropTypes.string.isRequired,
    className: PropTypes.string,
    valign: PropTypes.oneOf(['bottom', 'middle', 'text-bottom']),
    iconSize: PropTypes.oneOf(['auto', 'xs', 's', 'base', 'l', 'xl', 'xxl']),
    iconTitle: PropTypes.string,
    iconRole: PropTypes.string,
    iconPrefix: PropTypes.string,
}

AtomsIcon.defaultProps = {
    className: null,
    valign: null,
    iconSize: 'auto',
    iconTitle: null,
    iconRole: null,
    iconPrefix: '',
}

export default ComponentInjector.return('AtomsIcon', AtomsIcon)
