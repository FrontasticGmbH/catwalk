import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import productConnector from '../connector'

class ProductTitleTastic extends Component {
    render () {
        if (!this.props.product || !this.props.variant) {
            return null
        }

        let TitleHtmlComponent = this.props.tastic.schema.get('level')
        return (<div className='c-page-section'>
            <TitleHtmlComponent className='c-heading-gamma'>
                {this.props.product.name}
            </TitleHtmlComponent>
        </div>)
    }
}

ProductTitleTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
    product: PropTypes.object,
    variant: PropTypes.object,
}

ProductTitleTastic.defaultProps = {
    product: null,
    variant: null,
}

export default connect(productConnector)(ProductTitleTastic)
