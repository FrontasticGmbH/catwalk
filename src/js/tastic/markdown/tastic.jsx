import React, { Component } from 'react'
import PropTypes from 'prop-types'

import Markdown from '../../component/markdown'

class MarkdownTastic extends Component {
    render () {
        return <Markdown text={this.props.tastic.schema.get('text')} />
    }
}

MarkdownTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
}

MarkdownTastic.defaultProps = {
}

export default MarkdownTastic
