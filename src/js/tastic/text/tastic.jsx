//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { withTranslatedTasticData } from '../../component/withTranslatedTasticData'

class TextTastic extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        const { text } = this.props.data
        return <div className='text'>{text}</div>
    }
}

TextTastic.propTypes = {
    data: PropTypes.object.isRequired,
}

export default withTranslatedTasticData(TextTastic)
