import React, { Component } from 'react'
import PropTypes from 'prop-types'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import AtomsIcon from '../../atoms/icons/icon'

import Image from '../../../../js/image'

class MoleculesImage extends Component {
    render () {
        return (<figure className={'c-figure ' + this.props.className}>
            {!this.props.icon ? null : <AtomsIcon
                icon={this.props.icon}
                iconSize={this.props.iconSize}
                className='c-figure__icon c-badge c-badge--invert' />
            }
            <Image
                className='c-figure__image'
                media={this.props.media}
                cropRatio={this.props.ratio === 'custom' ? null : this.props.ratio} />
        </figure>)
    }
}

MoleculesImage.propTypes = {
    media: PropTypes.object.isRequired,
    className: PropTypes.string,
    ratio: PropTypes.oneOf(['8:1', '5:1', '3:1', '2:1', '4:3', '16:9', '1:1', 'custom']),
    icon: PropTypes.string,
    iconSize: PropTypes.oneOf(['auto', 'xs', 's', 'base', 'l', 'xl', 'xxl']),
}

MoleculesImage.defaultProps = {
    className: '',
    ratio: 'custom',
    icon: null,
    iconSize: 'xxl',
}

// These are just default props for the pattern library
MoleculesImage.testProps = {
    media: fixture.image.person,
    ratio: '16:9',
}

export default ComponentInjector.return('MoleculesImage', MoleculesImage)
