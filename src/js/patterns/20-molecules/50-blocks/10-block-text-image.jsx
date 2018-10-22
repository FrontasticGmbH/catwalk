import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import MoleculesImage from '../70-images/10-image'

class MoleculesBlockTextImage extends Component {
    render () {
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

// These are just default props for the pattern library
MoleculesBlockTextImage.testProps = {
    media: fixture.image.person,
    children: fixture.excerpt.medium,
}

export default ComponentInjector.return('MoleculesBlockTextImage', MoleculesBlockTextImage)
