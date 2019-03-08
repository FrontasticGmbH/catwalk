import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getTranslation } from 'frontastic-common'
import Image from './image'

class MediaImage extends Component {
    getCropRatio = () => {
        return this.props.media.ratio === 'custom' ? null : this.props.media.ratio
    }

    getOptions = () => {
        return {
            gravity: this.props.media.gravity,
        }
    }

    getTitle = () => {
        const translatedTitle = getTranslation(
            this.props.media.title,
            this.props.context.locale,
            this.props.context.project.defaultLanguage)

        if (translatedTitle.translated !== false || translatedTitle.text !== '') {
            return translatedTitle.text
        }

        return this.props.media.media.name
    }

    render () {
        return <Image
            className={this.props.className}
            media={this.props.media.media}
            title={this.getTitle()}
            cropRatio={this.getCropRatio()}
            options={this.getOptions()} />
    }
}

MediaImage.propTypes = {
    context: PropTypes.object.isRequired,
    media: PropTypes.object.isRequired,
    className: PropTypes.string,
}

MediaImage.defaultProps = {
    className: '',
}

export default connect(
    (globalState) => {
        return {
            context: globalState.app.context,
        }
    }
)(MediaImage)
