import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import { deprecate } from '@frontastic/common'
import MediaApi from '@frontastic/common/src/js/mediaApi'
import omit from '@frontastic/common/src/js/helper/omit'
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
            width: null,
            height: null,
        }
    }

    static mediaApi = new MediaApi()

    getAltText = () => {
        return this.props.title || this.props.media.name
    }

    static getInputImageDimensions (props) {
        const { forceWidth, forceHeight, media, width, height } = props

        if ((forceWidth || forceHeight) && media && media.width && media.height) {
            return [
                forceWidth || (forceHeight / media.height) * media.width,
                forceHeight || (forceWidth / media.width) * media.height,
            ]
        }

        // On initial load it can happen, especially in production, that
        // props.width and height are NULL. This caused a bunch of issues with
        // images being only a few pixels tall. So we do a NULL check on that.
        // If that's the case we check if the media object is there and if we
        // can use the metadata in there. Lastly it falls back to a device
        // default.
        let inputHeight = height
        if (height === null) {
            if (media) {
                inputHeight = media.height
            } else {
                inputHeight = null
            }
        }
        let inputWidth = width
        if (width === null) {
            if (media) {
                inputWidth = media.width
            } else {
                inputWidth = props.deviceType === 'mobile' ? 512 : 1024
            }
        }

        return [inputWidth, inputHeight]
    }

    static getDerivedStateFromProps (props, state) {
        const [inputWidth, inputHeight] = Image.getInputImageDimensions(props)

        const [width, height] = Image.mediaApi.getImageDimensions(
            props.media,
            inputWidth,
            inputHeight,
            props.cropRatio
        )

        // Obnly update actually rendered image width if the size of the image
        // differes in a relevant way (needs be larger, needs to be more then
        // three times smaller). Otherwise jsut keep the original image to not
        // load stuff again and again.
        if ((width > state.width) ||
            (height > state.height) ||
            (width < (state.width / 3))) {
            return {
                ...state,
                width,
                height,
            }
        } else {
            return state
        }
    }

    render () {
        if (typeof this.props.cropRatio === 'number') {
            deprecate('Numeric crop ratios are deprecated, please use a crop ratio like 3:4')
        }

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
            'deviceType',
        ]

        if (this.state.error || !this.state.width || !this.state.height) {
            return (
                <img
                    style={this.props.style}
                    width={this.state.width}
                    height={this.state.height}
                    alt={this.getAltText()}
                    src={NoImage}
                    {...omit(this.props, omitedProperties)}
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
                width={this.state.width}
                height={this.state.height}
                alt={this.getAltText()}
                src={Image.mediaApi.getImageLink(
                    this.props.media,
                    this.props.context.project.configuration,
                    this.state.width,
                    this.state.height,
                    this.props.cropRatio,
                    this.props.options
                )}
                srcSet={[1, 2].map((factor) => {
                    return [
                        Image.mediaApi.getImageLink(
                            this.props.media,
                            this.props.context.project.configuration,
                            this.state.width,
                            this.state.height,
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
                {...omit(this.props, omitedProperties)}
            />
        )
    }
}

Image.propTypes = {
    context: PropTypes.object.isRequired,
    deviceType: PropTypes.string.isRequired,
    media: PropTypes.object.isRequired,
    title: PropTypes.string,
    width: PropTypes.number,
    height: PropTypes.number,
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
    width: null,
    height: null,
}

export default connect((globalState) => {
    return {
        context: globalState.app.context,
        deviceType: globalState.renderContext.deviceType,
    }
})(sizer({ getSize: MediaApi.getElementDimensions })(Image))
