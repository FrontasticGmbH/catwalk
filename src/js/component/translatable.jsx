import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getTranslation } from 'frontastic-common'

class Translatable extends Component {
    render () {
        if (!this.props.value) {
            return null
        }

        let value = getTranslation(
            this.props.value,
            this.props.currentLocale,
            this.props.defaultLocale
        )

        if (value.locale !== this.props.currentLocale) {
            // Since the current text is not available in the locale
            // selected by the user, which is assumed to be the default for
            // the full document, we explicitely declare the language here
            // for this node.
            return <span lang={value.locale} className='untranslated'>{value.text}</span>
        } else {
            return value.text
        }
    }
}

Translatable.propTypes = {
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.object,
    ]),
    currentLocale: PropTypes.string.isRequired,
    defaultLocale: PropTypes.string.isRequired,
}

Translatable.defaultProps = {
    value: null,
}

export default connect(
    (globalState, props) => {
        return {
            currentLocale: globalState.app.context.locale,
            defaultLocale: globalState.app.context.project.defaultLanguage,
            value: props.value,
        }
    }
)(Translatable)
