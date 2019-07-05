import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {withTranslatedTasticData} from "../../component/withTranslatedTasticData";

class TextTastic extends Component {
    render () {
        const {text} = this.props
        return <div className='text'>{text}</div>
    }
}

TextTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
}

export default withTranslatedTasticData(TextTastic)
