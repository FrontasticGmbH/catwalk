//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Slider from '../../../component/slider'
import Zoom from '../../../component/zoom'
import productConnector from '../connector'
import NoImage from '../../../../layout/noImage.svg'
import RemoteImage from '../../../remoteImage'

class ProductImageTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            width: null,
        }
    }

    componentDidMount = () => {
        if (!this.refs.section) {
            return
        }

        this.setState({
            width: this.refs.section.clientWidth,
        })
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        if (!this.props.product || !this.props.variant) {
            return null
        }

        let uniqueImages = _.uniq(_.flatten(_.map(this.props.product.variants, 'images')))
        if (!uniqueImages.length) {
            uniqueImages.push(NoImage)
        }

        return (<div className='c-page-section' ref='section'>
            <Slider>
                {_.map(uniqueImages, (image) => {
                    return (<figure className='c-slider__item js-slider__item c-figure' key={image}>
                        {this.state.width ? <Zoom width={this.state.width} height={this.state.width / 3 * 2}>
                            {(x, y, scale) => {
                                return <RemoteImage
                                    style={{
                                        pointerEvents: scale === 1 ? 'auto' : 'none',
                                        transform: `translate3d(${x}px, ${y}px, 0) scale(${scale})`,
                                        transformOrigin: '0 0',
                                    }}
                                    className='c-teaser__image'
                                    url={image}
                                    alt={this.props.product.name}
                                    cropRatio='3:2'
                                    options={{ crop: 'pad', background: 'white' }}
                                />
                            }}
                        </Zoom> : null}
                    </figure>)
                })}
            </Slider>
        </div>)
    }
}

ProductImageTastic.propTypes = {
    product: PropTypes.object,
    variant: PropTypes.object,
}

ProductImageTastic.defaultProps = {
    product: null,
    variant: null,
}

export default connect(productConnector)(ProductImageTastic)
