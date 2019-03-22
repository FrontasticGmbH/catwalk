import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import sizer from 'react-sizer'
import _ from 'lodash'

import { MediaApi } from 'frontastic-common'
import NoImage from '../layout/noImage.svg'

/**
 * This component renders an image from the Media API. If you need to render an image from a URL, use RemoteImage!
 */
class Image extends Component {
    constructor(props) {
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
            'autoHeight',
        ]

        let [width, height] = this.mediaApi.getImageDimensions(
            this.props.media,
            this.props.width,
            this.props.height,
            this.props.cropRatio
        )

        if (this.state.error) {
            return (
                <img
                    style={this.props.style}
                    width={width}
                    height={height}
                    alt={this.props.title || this.props.media.name}
                    src={NoImage}
                    {..._.omit(this.props, omitedProperties)}
                />
            )
        }

        return (<img
            style={this.props.style}
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
                this.props.width,
                this.props.height,
                this.props.cropRatio,
                this.props.options
            )}
            srcSet={(_.map([1, 2], (factor) => {
                return [this.mediaApi.getImageLink(
                        this.props.media,
                        this.props.context.project.configuration,
                        this.props.width,
                        this.props.height,
                        this.props.cropRatio,
                        this.props.autoHeight,
                        this.props.options
                    ),
                    factor + 'x',
                ].join(' ')
            }).join(', ')}
            onError={() => {
                this.setState({ error: true })
            }}
            {..._.omit(this.props, omitedProps)}
        />)
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
    title: PropTypes.string,
    autoHeight: PropTypes.bool,
}

Image.defaultProps = {
    style: {},
    cropRatio: null,
    className: '',
}

export default sizer({
    getSize: MediaApi.getElementDimensions,
})(
    connect((globalState, props) => {
        let inferredProps = {
            ...props,
            context: globalState.app.context,
        }

        if (props.forceWidth && props.media && props.media.width && props.media.height) {
            inferredProps.width = props.forceWidth
            inferredProps.height = props.forceHeight || (props.forceWidth / props.media.width) * props.media.height
        }

        if (props.forceHeight && props.media && props.media.width && props.media.height) {
            inferredProps.width = props.forceWidth || (props.forceHeight / props.media.height) * props.media.width
            inferredProps.height = props.forceHeight
        }

        return inferredProps
    })(Image)
)
