import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import MediaApi from 'frontastic-common/src/js/mediaApi'
import sizer from './helper/reactSizer'
import NoImage from '../layout/noImage.svg'

/**
 * This component renders an image from the Media API. If you need to render an image from a URL, use RemoteImage!
 */
class Image extends Component {
    constructor (props) {
        super(props)

        this.state = {
            loading: true,
            error: false,
        }
    }

    mediaApi = new MediaApi()

    getAltText = () => {
        return this.props.title || this.props.media.name
    }

    getInputImageDimensions = () => {
        const { forceWidth, forceHeight, media, width, height } = this.props

        if ((forceWidth || forceHeight) && media && media.width && media.height) {
            return [
                forceWidth || (forceHeight / media.height) * media.width,
                forceHeight || (forceWidth / media.width) * media.height,
            ]
        }

        // on initial load it happens, especially in production, that props.width and height are NULL.
        // This caused a bunch of issues with images being only a few pixels tall.
        // So we do a NULL check on that. If that's the case we check if the media object is there
        // and if we can use the metadata in there. Lastly it falls back to 0.
        let inputHeight = height
        if (height === null) {
            if (media) {
                inputHeight = media.height
            } else {
                inputHeight = 0
            }
        }
        let inputWidth = width
        if (width === null) {
            if (media) {
                inputWidth = media.width
            } else {
                inputWidth = 0
            }
        }

        return [inputWidth, inputHeight]
    }

    render () {
        const omitedProperties = [
            'context',
            'media',
            'width',
            'height',
            'forceWidth',
            'forceHeight',
            'style',
            'cropRatio',
            'options',
            'title',
            'url',
            'alt',
            'dispatch',
        ]

        const [inputWidth, inputHeight] = this.getInputImageDimensions()

        const [width, height] = this.mediaApi.getImageDimensions(
            this.props.media,
            inputWidth,
            inputHeight,
            this.props.cropRatio
        )

        if (this.state.error) {
            return (
                <img
                    style={this.props.style}
                    width={width}
                    height={height}
                    alt={this.getAltText()}
                    src={NoImage}
                    {..._.omit(this.props, omitedProperties)}
                />
            )
        }

        return (
            <img
                style={this.props.style}
                loading={this.props.loading}
                className={this.props.className + (this.state.loading ? 'loading' : 'loaded')}
                onLoad={() => {
                    this.setState({ loading: false })
                }}
                width={width}
                height={height}
                alt={this.getAltText()}
                src={this.mediaApi.getImageLink(
                    this.props.media,
                    this.props.context.project.configuration,
                    inputWidth,
                    inputHeight,
                    this.props.cropRatio,
                    this.props.options
                )}
                srcSet={_.map([1, 2], (factor) => {
                    return [
                        this.mediaApi.getImageLink(
                            this.props.media,
                            this.props.context.project.configuration,
                            inputWidth,
                            inputHeight,
                            this.props.cropRatio,
                            this.props.options,
                            factor
                        ),
                        factor + 'x',
                    ].join(' ')
                }).join(', ')}
                onError={() => {
                    this.setState({ error: true })
                }}
                {..._.omit(this.props, omitedProperties)}
            />
        )
    }
}

Image.propTypes = {
    context: PropTypes.object.isRequired,
    media: PropTypes.object.isRequired,
    title: PropTypes.string,
    width: PropTypes.number.isRequired,
    height: PropTypes.number.isRequired,
    forceWidth: PropTypes.number,
    forceHeight: PropTypes.number,
    style: PropTypes.object,
    cropRatio: PropTypes.oneOfType([
        PropTypes.string,
        // @DEPRECATED:
        PropTypes.number,
    ]),
    className: PropTypes.string,
    options: PropTypes.object,
    loading: PropTypes.oneOf(['lazy', 'auto', 'eager']),
}

Image.defaultProps = {
    style: {},
    cropRatio: null,
    className: '',
    loading: 'lazy',
}

export default connect((globalState) => {
    return {
        context: globalState.app.context,
    }
})(sizer({ getSize: MediaApi.getElementDimensions })(Image))
