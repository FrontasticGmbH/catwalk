import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import sizer from 'react-sizer'
import _ from 'lodash'
import 'lazysizes'

import MediaApi from 'frontastic-common/src/js/mediaApi'
import NoImage from '../layout/noImage.svg'

class RemoteImage extends Component {
    constructor (props) {
        super(props)

        this.state = {
            loading: true,
            error: false,
        }
    }

    mediaApi = new MediaApi()

    render () {
        let [width, height] = this.mediaApi.getImageDimensions(
            this.props.url,
            this.props.width,
            this.props.height,
            this.props.cropRatio
        )

        const log = this.props.url === 'https://cos.flexvault.de/shop-images/8719154233166_FRONT_InStyle_HD.jpg'
        log && console.log('Dimensions', width, height)

        if (this.state.error) {
            return (
                <img
                style={{ display: 'block', width: width, height: height, ...this.props.style }}
                    width={width}
                    height={height}
                    alt={this.props.alt}
                    src={NoImage}
                    {..._.omit(this.props, [
                        'style',
                        'className',
                        'context',
                        'url',
                        'alt',
                        'cropRatio',
                        'width',
                        'height',
                        'dispatch',
                        'options',
                    ])}
                />
            )
        }

        return (
            <img
                style={{ display: 'block', width: width, height: height, ...this.props.style }}
                loading='lazy'
                className={'lazyload ' + this.props.className}
                onLoad={() => {
                    this.setState({ loading: false })
                }}
                width={width}
                height={height}
                alt={this.props.alt}
                data-src={this.mediaApi.getImageLink(
                    this.props.url,
                    this.props.context.project.configuration,
                    this.props.width,
                    this.props.height,
                    this.props.cropRatio,
                    this.props.options
                )}
                data-src-set={_.map([1, 2], (factor) => {
                    return [
                        this.mediaApi.getImageLink(
                            this.props.url,
                            this.props.context.project.configuration,
                            this.props.width,
                            this.props.height,
                            this.props.cropRatio,
                            this.props.options
                        ),
                        factor + 'x',
                    ].join(' ')
                }).join(', ')}
                onError={() => {
                    this.setState({ error: true })
                }}
                {..._.omit(this.props, [
                    'style',
                    'className',
                    'context',
                    'url',
                    'alt',
                    'cropRatio',
                    'width',
                    'height',
                    'dispatch',
                    'options',
                ])}
            />
        )
    }
}

RemoteImage.propTypes = {
    context: PropTypes.object.isRequired,
    url: PropTypes.string.isRequired,
    alt: PropTypes.string.isRequired,
    width: PropTypes.number.isRequired,
    height: PropTypes.number.isRequired,
    style: PropTypes.object,
    cropRatio: PropTypes.oneOfType([
        PropTypes.string,
        // @DEPRECATED:
        PropTypes.number,
    ]).isRequired,
    className: PropTypes.string,
    options: PropTypes.object,
}

RemoteImage.defaultProps = {
    style: {},
    cropRatio: null,
    className: '',
}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
    }
})(
    sizer({
        getSize: MediaApi.getElementDimensions,
    })(RemoteImage)
)
