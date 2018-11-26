import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import _ from 'lodash'

import app from '../../app/app'
import Entity from '../../app/entity'
import Loading from '../../app/loading'

import MoleculesLineItem from '../../patterns/molecules/cart/line-item'
import Summary from './summary'

class CartTastic extends Component {
    render () {
        let cart = null
        if (this.props.cart.data) {
            cart = this.props.cart.data
        }

        return (<div className='c-cart o-layout'>
            <Loading entity={this.props.cart} />
            {!cart ? null : (<Fragment>
                <div className='c-cart__items o-layout__item u-1/1 u-3/4@lap u-3/4@desk'>
                    <h2 className='c-heading-beta'>Warenkorb ({cart.getProductCount()} Artikel)</h2>
                    {!cart.lineItems.length ?
                        <p className='c-cart__message'>Nothing in here…</p> :
                        _.map(cart.lineItems, (lineItem) => {
                            return (<MoleculesLineItem
                                key={lineItem.lineItemId}
                                lineItem={lineItem}
                                onRemove={(lineItem) => {
                                    app.getLoader('cart').removeLineItem({ lineItemId: lineItem.lineItemId })
                                }}
                                onChangeCount={(lineItem, count) => {
                                    app.getLoader('cart').updateLineItem({
                                        lineItemId: lineItem.lineItemId,
                                        count: count,
                                    })
                                }}
                                onAddToWishlist={(lineItem, count) => {
                                    console.warn('@TODO: Add to wishlist', lineItem, count)
                                }}
                                showProductPicture={this.props.tastic.schema.get('showProductPicture')}
                            />)
                        })
                    }
                </div>
                <div className='o-layout__item u-1/1 u-1/4@lap u-1/4@desk'>
                    <Summary cart={cart} />
                    <button
                        className='c-button c-button--full c-button--primary'
                        onClick={() => {
                            app.getRouter().push('Frontastic.Frontend.Master.Checkout.checkout')
                        }}
                    >
                        Checkout
                    </button>
                </div>
            </Fragment>)}
        </div>)
    }
}

CartTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
    cart: PropTypes.instanceOf(Entity).isRequired,
}

CartTastic.defaultProps = {}

export default connect(
    (globalState, props) => {
        return {
            cart: globalState.cart.cart,
        }
    }
)(CartTastic)
