import React, { Component } from 'react'
import PropTypes from 'prop-types'

import Markdown from '../../component/markdown'

class CategoryDescriptionTastic extends Component {
    render () {
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
