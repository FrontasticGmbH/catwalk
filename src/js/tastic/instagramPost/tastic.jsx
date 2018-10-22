import React, { Component } from 'react'
import PropTypes from 'prop-types'

import InstagramEmbed from 'react-instagram-embed'

class InstagramPost extends Component {
    render () {
        if (!this.props.tastic.schema.get('post-url')) {
            return null
        }

        return (
            <InstagramEmbed url={this.props.tastic.schema.get('post-url')} />
        )
    }
}

InstagramPost.propTypes = {
    tastic: PropTypes.object.isRequired,
}

InstagramPost.defaultProps = {}

export default InstagramPost
