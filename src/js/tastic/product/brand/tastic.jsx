//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import productConnector from '../connector'

class ProductBrandTastic extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.props.product || !this.props.variant || !this.props.variant.attributes.designer) {
            return null
        }

        let BrandHtmlComponent = this.props.tastic.schema.get('level')
        return (<div className='c-page-section'>
            <BrandHtmlComponent className='c-heading-zeta'>
                {this.props.variant.attributes.designer.label || this.props.variant.attributes.designer}
            </BrandHtmlComponent>
        </div>)
    }
}

ProductBrandTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
    product: PropTypes.object,
    variant: PropTypes.object,
}

ProductBrandTastic.defaultProps = {
    product: null,
    variant: null,
}

export default connect(productConnector)(ProductBrandTastic)
