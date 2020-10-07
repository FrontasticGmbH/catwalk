//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import ProductTitle from './title/tastic.jsx'
import ProductImage from './image/tastic.jsx'
import ProductPrice from './price/tastic.jsx'
import ProductAddToCart from './addToCart/tastic.jsx'
import ProductDescription from './description/tastic.jsx'

class ProductTastic extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<Fragment>
            <ProductImage data={this.props.data} tastic={this.props.tastic} rawData={this.props.rawData} />
            <ProductTitle data={this.props.data} tastic={this.props.tastic} rawData={this.props.rawData} />
            <ProductPrice data={this.props.data} tastic={this.props.tastic} rawData={this.props.rawData} />
            <ProductAddToCart data={this.props.data} tastic={this.props.tastic} rawData={this.props.rawData} />
            <ProductDescription data={this.props.data} tastic={this.props.tastic} rawData={this.props.rawData} />
        </Fragment>)
    }
}

ProductTastic.propTypes = {
    data: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    rawData: PropTypes.object.isRequired,
}

ProductTastic.defaultProps = {
}

export default ProductTastic
