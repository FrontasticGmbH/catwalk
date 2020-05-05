import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import Markdown from '../../component/markdown'

class MarkdownTastic extends Component {
    render () {
        return <Markdown
            text={this.props.data.text}
            className={classnames(
                's-text',
                'c-markdown',
                'c-markdown--align-' + this.props.data.align,
                'c-markdown--padding-' + this.props.data.padding
            )}
        />
    }
}

MarkdownTastic.propTypes = {
    data: PropTypes.object.isRequired,
}

MarkdownTastic.defaultProps = {
}

export default MarkdownTastic
