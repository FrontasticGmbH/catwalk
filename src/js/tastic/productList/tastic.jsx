//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Grid from '../../patterns/atoms/grid/grid'
import MoleculesProductTeaser from '../../patterns/molecules/teasers/product-teaser'

class ProductListTastic extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        let productList = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!productList) {
            return null
        }

        let showPercent = this.props.tastic.schema.get('showPercent') || true
        let showStrikePrice = this.props.tastic.schema.get('showStrikePrice') || true

        return (
            <Grid className='c-product-listing'>
                {_.map(productList.items, (product) => {
                    return (
                        <div key={product.productId} className='c-product-listing__item'>
                            <MoleculesProductTeaser
                                product={product}
                                showPercent={showPercent}
                                showStrikePrice={showStrikePrice}
                            />
                        </div>
                    )
                })}
            </Grid>
        )
    }
}

ProductListTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

ProductListTastic.defaultProps = {}

export default ProductListTastic
