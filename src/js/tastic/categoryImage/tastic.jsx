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
        if (!this.props.node.configuration.displayImage) {
            return null
        }

        let image = this.props.node.configuration.displayImage
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
