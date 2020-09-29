//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import PropTypes from 'prop-types'

import InstagramEmbed from 'react-instagram-embed'

class InstagramPost extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
