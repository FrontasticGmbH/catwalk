//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

import MoleculesImage from '../images/image'

class MoleculesBlockTextImage extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<div className={classnames(this.props.className, {
            'o-block': true,
            'o-block--media-auto': true,
            'o-block--media-reverse': this.props.reverse,
        })}>
            <MoleculesImage media={this.props.media} className='u-1/2' />
            <div className='o-block__body'>
                {this.props.children}
            </div>
        </div>)
    }
}

MoleculesBlockTextImage.propTypes = {
    media: PropTypes.object.isRequired,
    children: PropTypes.node,
    className: PropTypes.string,
    reverse: PropTypes.bool,
}

MoleculesBlockTextImage.defaultProps = {
    className: '',
    children: null,
    reverse: false,
}

export default ComponentInjector.return('MoleculesBlockTextImage', MoleculesBlockTextImage)
