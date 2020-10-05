//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Image from '../../image'

class CategoryImageTastic extends Component {
    optionsFromSettings = (imageSettings = {}) => {
        return _.omit(
            imageSettings,
            ['media', 'ratio', 'width', 'height']
        )
    }

    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.props.node.configuration.displayMedia) {
            return null
        }

        let image = this.props.node.configuration.displayMedia
        return (<Image
            media={image.media || {}}
            cropRatio='5:1'
            options={this.optionsFromSettings(image)}
            className='c-hero__image'
        />)
    }
}

CategoryImageTastic.propTypes = {
    node: PropTypes.object.isRequired,
}

CategoryImageTastic.defaultProps = {
}

export default CategoryImageTastic
