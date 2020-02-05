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
        if (
            (this.props.forceWidth || this.props.forceHeight) &&
            this.props.media &&
            this.props.media.width &&
            this.props.media.height
        ) {

            return [
                this.props.forceWidth || (this.props.forceHeight / this.props.media.height) * this.props.media.width,
                this.props.forceHeight || (this.props.forceWidth / this.props.media.width) * this.props.media.height,
            ]
        }

        return [this.props.width, this.props.height]
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
