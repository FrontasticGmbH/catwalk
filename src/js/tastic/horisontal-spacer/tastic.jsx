//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import PropTypes from 'prop-types'

class HorizontalSpacerTastic extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
