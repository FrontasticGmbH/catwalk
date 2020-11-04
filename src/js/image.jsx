import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import { deprecate, omit, MediaApi } from '@frontastic/common'
import getImageDimensions from './helper/getImageDimension'
import sizer from './helper/reactSizer'
import NoImage from '../layout/noImage2.svg'

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

    static getDerivedStateFromProps (props, state) {
        return getImageDimensions(Image.mediaApi, props, state)
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

        if (this.state.error || !(this.state.width || this.state.height)) {
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
                srcSet={[1, 2]
                    .map((factor) => {
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
                    })
                    .join(', ')}
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

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
        deviceType: globalState.renderContext.deviceType,
    }
})(sizer({ getSize: MediaApi.getElementDimensions })(Image))
