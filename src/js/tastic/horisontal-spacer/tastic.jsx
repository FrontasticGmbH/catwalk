import React, { Component } from 'react'
import PropTypes from 'prop-types'

class HorizontalSpacerTastic extends Component {
    render () {
        const spaceInPx = this.props.tastic.schema.get('spaceInPx') || 24

        return (
            <div
                className='horisontal-spacer-tastic'
                style={{
                width: '100%',
                height: `${spaceInPx}px`,
            }} />
        )
    }
}

HorizontalSpacerTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
}

HorizontalSpacerTastic.defaultProps = {
}

export default HorizontalSpacerTastic
