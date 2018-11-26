import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Slider from '../../component/slider'
import MoleculesProductTeaser from '../../patterns/molecules/teasers/product-teaser'

class ProductSliderTastic extends Component {
    render () {
        let productList = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!productList) {
            return null
        }

        let productsToShow = _.take(productList.items, this.props.tastic.schema.get('productCount'))
        let showPercent = this.props.tastic.schema.get('showPercent') || true
        let showStrikePrice = this.props.tastic.schema.get('showStrikePrice') || true

        return (<Slider>
            {_.map(productsToShow, (product) => {
                return (<div className='c-slider__item  js-slider__item  u-3/4  u-2/5@lap  u-2/7@desk' key={product.productId}>
                    <MoleculesProductTeaser
                        product={product}
                        showPercent={showPercent}
                        showStrikePrice={showStrikePrice}
                    />
                </div>)
            })}
        </Slider>)
    }
}

ProductSliderTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

ProductSliderTastic.defaultProps = {
}

export default ProductSliderTastic
