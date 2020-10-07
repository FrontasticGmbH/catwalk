//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import _ from 'lodash'

import Markdown from '../../../component/markdown'
import productConnector from '../connector'

class ProductDescriptionTastic extends Component {
    ucFirst (string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        if (!this.props.product) {
            return null
        }

        if (this.props.variant && !this.props.product.description) {
            let displayAttributes = [
                'color',
                'designer',
                'size',
                'style',
            ]

            return (<div className='s-text'>
                <ul>
                    {_.map(displayAttributes, (attribute) => {
                        if (!this.props.variant.attributes[attribute]) {
                            return null
                        }

                        return (<li key={attribute}>
                            <strong>{this.ucFirst(attribute)}</strong>: {this.props.variant.attributes[attribute].label || this.props.variant.attributes[attribute]}
                        </li>)
                    })}
                </ul>
            </div>)
        }

        return <Markdown text={this.props.product.description} />
    }
}

ProductDescriptionTastic.propTypes = {
    product: PropTypes.object,
    variant: PropTypes.object,
}

ProductDescriptionTastic.defaultProps = {
    product: null,
    variant: null,
}

export default connect(productConnector)(ProductDescriptionTastic)
