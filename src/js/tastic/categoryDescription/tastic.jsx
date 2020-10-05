//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import PropTypes from 'prop-types'

import Markdown from '../../component/markdown'

class CategoryDescriptionTastic extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.props.node.configuration.displayDescription) {
            return null
        }

        return <Markdown text={this.props.node.configuration.displayDescription} />
    }
}

CategoryDescriptionTastic.propTypes = {
    node: PropTypes.object.isRequired,
}

CategoryDescriptionTastic.defaultProps = {
}

export default CategoryDescriptionTastic
