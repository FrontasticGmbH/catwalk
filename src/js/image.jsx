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

    render () {
        let omitedProperties = ['context', 'media', 'title', 'url', 'alt', 'cropRatio', 'width', 'height', 'dispatch', 'options']

        let [width, height] = this.mediaApi.getImageDimensions(
            this.props.media,
            this.props.width,
            this.props.height,
            this.props.cropRatio
        )

        if (this.state.error) {
            return (<img
                style={this.props.style}
                width={width}
                height={height}
                alt={this.getAltText()}
                src={NoImage}
                {...(_.omit(this.props, omitedProperties))}
            />)
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
                    this.props.options
                ), factor + 'x'].join(' ')
            })).join(', ')}
            onError={() => {
                this.setState({ error: true })
            }}
            {...(_.omit(this.props, omitedProperties))} />)
    }
}

Image.propTypes = {
    context: PropTypes.object.isRequired,
    media: PropTypes.object.isRequired,
    title: PropTypes.string,
    width: PropTypes.number.isRequired,
    height: PropTypes.number.isRequired,
    style: PropTypes.object,
    cropRatio: PropTypes.oneOfType([
        PropTypes.string,
        // @DEPRECATED:
        PropTypes.number,
    ]),
    className: PropTypes.string,
    options: PropTypes.object,
}

Image.defaultProps = {
    style: {},
    cropRatio: null,
    className: '',
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(sizer({
    getSize: MediaApi.getElementDimensions,
})(Image))
