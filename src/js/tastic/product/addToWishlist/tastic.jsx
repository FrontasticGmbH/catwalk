import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import app from '../../../app/app'
import SvgIcon from '../../../patterns/atoms/icons/icon'
import Fade from '../../../component/fade'
import productConnector from '../connector'

class ProductAddToWishlistTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            selectedWishlist: 0,
            indicateBaskedAdded: false,
        }
    }

    ucfirst = (inString) => {
        return inString.charAt(0).toUpperCase() + inString.slice(1)
    }

    selectVariant = (variantIndex) => {
        let parameters = _.extend(
            {},
            this.props.route.parameters,
            { _variant: variantIndex }
        )
        app.getRouter().replace(this.props.route.route, parameters)
    }

    addToWishlist = (variantIndex) => {
        app.getLoader('wishlist').add(this.props.product, this.props.variant, 1, this.state.selectedWishlist)

        this.setState(
            { indicateBaskedAdded: true },
            _.debounce(() => {
                this.setState({ indicateBaskedAdded: false })
            }, 600)
        )
    }

    getVariantAttributes = () => {
        let attributeValues = {}
        for (let variant of this.props.product.variants) {
            for (let attribute in variant.attributes) {
                if (!attributeValues[attribute]) {
                    attributeValues[attribute] = []
                }

                attributeValues[attribute].push(variant.attributes[attribute])
            }
        }

        return _.keys(_.pickBy(
            _.mapValues(attributeValues, _.uniq),
            (values) => {
                return values.length > 1
            }
        ))
    }

    render () {
        if (!this.props.product || !this.props.variant) {
            return null
        }

        let showNameInVariants = this.props.tastic.schema.get('showNameInVariants') || false
        let showPriceInVariants = this.props.tastic.schema.get('showPriceInVariants') || true
        let variantAttributes = _.intersection(
            this.getVariantAttributes(),
            this.props.tastic.schema.get('variantAttributes') || ['color', 'size', 'style']
        )

        let wishlists = this.props.data.wishlists || []

        return (<div className='c-page-section'>
            <Fade in={this.state.indicateBaskedAdded}>
                <SvgIcon
                    className='o-icon c-figure__icon c-badge c-badge--invert'
                    style={{
                        // Element should be in the center of the viewport
                        position: 'fixed',
                    }}
                    icon='check'
                    data-icon-size='xxl'
                />
            </Fade>
            <form className='c-form' action=''>
                {wishlists.length > 1 ?
                    <div className='c-form__item'>
                        <select
                            className='c-form__select'
                            value={this.state.selectedWishlist}
                            onChange={(event) => {
                                this.setState({ selectedWishlist: event.currentTarget.value })
                            }}
                        >
                            <option value={0}>Select Wishlist</option>
                            {_.map(wishlists, (wishlist) => {
                                return (<option value={wishlist.wishlistId} key={wishlist.wishlistId}>
                                    {wishlist.name}
                                </option>)
                            })}
                        </select>
                    </div> : null}
                {this.props.product.variants.length > 1 ?
                    <div className='c-form__item'>
                        <select
                            className='c-form__select'
                            value={this.props.selectedVariant}
                            onChange={(event) => {
                                this.selectVariant(event.currentTarget.value)
                            }}
                        >
                            {_.map(this.props.product.variants, (variant, index) => {
                                return (<option value={index} key={index}>
                                    {showNameInVariants ? this.props.product.name + ' ' : null}
                                    {_.map(variantAttributes, (attribute) => {
                                        return this.ucfirst(attribute) + ': ' + variant.attributes[attribute]
                                    }).join('; ')}
                                    {showPriceInVariants ?
                                        ' – ' + (variant.price / 100).toLocaleString(
                                            (this.props.context.locale).replace('_', '-'),
                                            { style: 'currency', currency: variant.currency }
                                        )
                                    : null}
                                </option>)
                            })}
                        </select>
                    </div> : null}

                <div className='c-form__item'>
                    <button
                        disabled={!this.props.variant.isOnStock}
                        className='c-button  c-button--primary  c-button--full'
                        onClick={(event) => {
                            event.preventDefault()
                            this.addToWishlist(this.props.selectedVariant)
                        }}
                    >
                        Add to wishlist
                    </button>
                </div>
            </form>
        </div>)
    }
}

ProductAddToWishlistTastic.propTypes = {
    context: PropTypes.object.isRequired,
    route: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    product: PropTypes.object,
    variant: PropTypes.object,
    option: PropTypes.object,
    selectedVariant: PropTypes.number,
}

ProductAddToWishlistTastic.defaultProps = {
    product: null,
    variant: null,
    selectVariant: 0,
}

export default connect(
    (globalState, props) => {
        return {
            context: globalState.app.context,
            route: globalState.app.route,
            ...props,
        }
    }
)(connect(productConnector)(ProductAddToWishlistTastic))
