import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getTranslation } from 'frontastic-common'

class TextTastic extends Component {
    render() {

        let text = getTranslation(
            this.props.tastic.schema.get('text'),
            this.props.context.locale,
            this.props.context.project.defaultLanguage
        )

        return <div className='text'>{text.text}</div>
    }
}

TextTastic.propTypes = {
    context: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

TextTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            context: globalState.app.context,
            ...props,
        }
    }
)(TextTastic)
