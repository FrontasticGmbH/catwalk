import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import _ from 'lodash'

import Reference from '../../component/reference'
import { getTranslation } from 'frontastic-common'

import MoleculesHero from '../../patterns/molecules/heros/hero'

class ImageBannerTastic extends Component {
    render () {
        const imageSettings = this.props.tastic.schema.get('image')
        if (!imageSettings) {
            return null
        }

        const size = this.props.tastic.schema.get('size') || 'normal'
        const verticalAlign = this.props.tastic.schema.get('verticalAlign') || 'center'
        const horizontalAlign = this.props.tastic.schema.get('horizontalAlign') || 'center'

        return (<Reference reference={this.props.data.link || {
            type: null,
            target: null,
        }}>
            <MoleculesHero
                media={imageSettings.media || {}}
                ratio={imageSettings.ratio}
                size={size}
                verticalAlign={verticalAlign}
                horizontalAlign={horizontalAlign}
                caption={getTranslation(
                    this.props.tastic.schema.get('caption'),
                    this.props.context.locale,
                    this.props.context.project.defaultLanguage
                ).text}
            />
        </Reference>)
    }

    optionsFromSettings = (imageSettings = {}) => {
        return _.omit(
            imageSettings,
            ['media', 'ratio', 'width', 'height']
        )
    }
}

ImageBannerTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

ImageBannerTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(ImageBannerTastic)
