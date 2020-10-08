import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import { deprecate, omit, MediaApi } from '@frontastic/common'
import NoImage from '../layout/noImage.svg'
import sizer from './helper/reactSizer'

class RemoteImage extends Component {
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

    static getInputImageDimensions (props) {
        const { forceWidth, forceHeight, width, height } = props
        const cropRatio = RemoteImage.mediaApi.getFloatRatio(null, props.cropRatio)

        if (forceWidth || forceHeight) {
            return [
                forceWidth || null,
                forceHeight || null,
            ]
        }

        // On initial load it can happen, especially in production, that
        // state.width and height are NULL. This caused a bunch of issues with
        // images being only a few pixels tall. So we do a NULL check on that.
        // If that's the case we check if the media object is there and if we
        // can use the metadata in there. Lastly it falls back to a device
        // default.
        let inputHeight = height || null
        let inputWidth = width || (height ? null : (props.deviceType === 'mobile' ? 512 : 1024))

        return [inputWidth, inputHeight]
    }

    static getDerivedStateFromProps (props, state) {
        const [inputWidth, inputHeight] = RemoteImage.getInputImageDimensions(props)

        const [width, height] = RemoteImage.mediaApi.getImageDimensions(
            null,
            inputWidth,
            inputHeight,
            props.cropRatio
        )

        // Obnly update actually rendered image width if the size of the image
        // differes in a relevant way (needs be larger, needs to be more then
        // three times smaller). Otherwise jsut keep the original image to not
        // load stuff again and again.
        if ((width > (state.width * 1.25)) ||
            (height > (state.height * 1.25)) ||
            (width < (state.width / 3)) ||
            (Math.abs(width / height - state.width / state.height) > .01)) {
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

        let [width, height] = RemoteImage.mediaApi.getImageDimensions(
            this.props.url,
            this.state.width,
            this.state.height,
            this.props.cropRatio
        )

        if (this.state.error || !width || !height) {
            return (
                <img
                    style={this.props.style}
                    width={width}
                    height={height}
                    alt={this.props.alt}
                    // @TODO: Some blurred image would be great, because this
                    // can also happen during loading. But this is ahrd for
                    // random remote images:
                    src={NoImage}
                    {...omit(this.props, [
                        'context',
                        'url',
                        'alt',
                        'cropRatio',
                        'width',
                        'height',
                        'dispatch',
                        'options',
                        'deviceType',
                    ])}
                />
            )
        }

        return (
            <img
                style={this.props.style}
                loading={this.props.loading}
                className={this.state.loading ? 'loading' : 'loaded'}
                onLoad={() => {
                    this.setState({ loading: false })
                }}
                width={width}
                height={height}
                alt={this.props.alt}
                src={RemoteImage.mediaApi.getImageLink(
                    this.props.url,
                    this.props.context.project.configuration,
                    this.state.width,
                    this.state.height,
                    this.props.cropRatio,
                    this.props.options
                )}
                srcSet={[1, 2].map((factor) => {
                    return [
                        RemoteImage.mediaApi.getImageLink(
                            this.props.url,
                            this.props.context.project.configuration,
                            this.state.width,
                            this.state.height,
                            this.props.cropRatio,
                            this.props.options
                        ),
                        factor + 'x',
                    ].join(' ')
                }).join(', ')}
                onError={() => {
                    this.setState({ error: true })
                }}
                {...omit(this.props, [
                    'context',
                    'url',
                    'alt',
                    'cropRatio',
                    'width',
                    'height',
                    'dispatch',
                    'options',
                    'deviceType',
                ])}
            />
        )
    }
}

RemoteImage.propTypes = {
    context: PropTypes.object.isRequired,
    deviceType: PropTypes.string.isRequired,
    url: PropTypes.string.isRequired,
    alt: PropTypes.string.isRequired,
    width: PropTypes.number,
    height: PropTypes.number,
    loading: PropTypes.oneOf(['lazy', 'auto', 'eager']),
    style: PropTypes.object,
    cropRatio: PropTypes.oneOfType([
        PropTypes.string,
        // @DEPRECATED:
        PropTypes.number,
    ]).isRequired,
    options: PropTypes.object,
}

RemoteImage.defaultProps = {
    style: {},
    cropRatio: null,
    loading: 'lazy',
}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
        deviceType: globalState.renderContext.deviceType,
    }
})(
    sizer({
        getSize: MediaApi.getElementDimensions,
    })(RemoteImage)
)
