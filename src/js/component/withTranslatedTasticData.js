import React from 'react'
import { connect } from 'react-redux'
import { translateTasticData } from '../helper/translateTasticData'
import PropTypes from 'prop-types'

/**
 * Higher order component for tastics that takes care of translating tastic fields.
 *
 * This actually means that you probably do not need to use <Translatable /> in your tastics anymore,
 * as all translated fields are already being passed as translated strings.
 *
 * @example
 * // The following tastic has a tastic field "text" that is translatable.
 * class TextTastic extends Component {
 *  render () {
 *      const {text} = this.props.data
 *      return <div className='text'>{text}</div>
 *  }
 * }
 *
 * export default withTranslatedTasticData(TextTastic)
 *
 * @param  {React.ComponentType<Props>} WrappedComponent
 * @returns {React.ComponentType<Props>}
 */
export const withTranslatedTasticData = (WrappedComponent) => {
    class WithTranslatedTasticData extends React.Component {
        render () {
            const { context, data, tastic } = this.props

            const translatedData = translateTasticData(data, tastic.schema, context)

            return <WrappedComponent {...this.props} data={translatedData} />
        }
    };

    WithTranslatedTasticData.displayName = `WithTranslatedTasticData(${getDisplayName(WrappedComponent)})`

    WithTranslatedTasticData.propTypes = {
        context: PropTypes.object.isRequired,
        data: PropTypes.object.isRequired,
        tastic: PropTypes.object.isRequired,
    }

    return connect(
        (globalState) => {
            return {
                context: globalState.app.context,
            }
        }
    )(WithTranslatedTasticData)
}

const getDisplayName = (WrappedComponent) => {
    return WrappedComponent.displayName || WrappedComponent.name || 'UnknownComponent'
}

export default withTranslatedTasticData
