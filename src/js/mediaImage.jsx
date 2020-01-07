import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import getTranslation from './getTranslation'
import Image from './image'

class MediaImage extends Component {
    getCropRatio = () => {
        if (this.props.ratio === 'custom') {
            return null
        }

        if (this.props.ratio && this.props.ratio !== '') {
            return this.props.ratio
        }

        return this.props.media.ratio === 'custom' ? null : this.props.media.ratio
    }

    getOptions = () => {
        return {
            gravity: this.props.media.gravity,
            ...this.props.options,
        }
    }

    getTitle = () => {
        if (this.props.title && this.props.title !== '') {
            return this.props.title
        }

        const translatedTitle = getTranslation(
            this.props.media.title,
            this.props.context.locale,
            this.props.context.project.defaultLanguage
        )

        if (translatedTitle.translated !== false || translatedTitle.text !== '') {
            return translatedTitle.text
        }

        return this.props.media.media.name
    }

    render () {
        if (!this.props.media || !this.props.media.media) {
            return null
        }

        return (
            <Image
                className={this.props.className}
                media={this.props.media.media}
                title={this.getTitle()}
                cropRatio={this.getCropRatio()}
                options={this.getOptions()}
                forceWidth={this.props.width}
                forceHeight={this.props.height}
            />
        )
    }
}

MediaImage.propTypes = {
    context: PropTypes.object.isRequired,
    media: PropTypes.object.isRequired,
    className: PropTypes.string,
    options: PropTypes.object,
    title: PropTypes.string,
    ratio: PropTypes.string,
    width: PropTypes.number,
    height: PropTypes.number,
}

MediaImage.defaultProps = {
    className: '',
    options: {},
}

export default connect((globalState) => {
    return {
        context: globalState.app.context,
    }
})(MediaImage)
