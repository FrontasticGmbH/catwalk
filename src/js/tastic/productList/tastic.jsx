import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import MoleculesProductTeaser from '../../patterns/20-molecules/40-teasers/10-product-teaser'

class ProductListTastic extends Component {
    render () {
        let productList = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!productList) {
            return null
        }

        let showPercent = this.props.tastic.schema.get('showPercent') || true
        let showStrikePrice = this.props.tastic.schema.get('showStrikePrice') || true

        return (<div className='o-layout'>
            {_.map(productList.items, (product) => {
                return (<div key={product.productId} className='o-layout__item u-1/2 u-1/3@lap u-1/4@desk'>
                    <MoleculesProductTeaser
                        product={product}
                        showPercent={showPercent}
                        showStrikePrice={showStrikePrice}
                    />
                </div>)
            })}
        </div>)
    }
}

ProductListTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

ProductListTastic.defaultProps = {
}

export default ProductListTastic
