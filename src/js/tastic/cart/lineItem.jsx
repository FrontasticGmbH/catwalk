import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import app from '../../app/app'
import Price from '../../patterns/10-atoms/80-prices/10-price'
import RemoteImage from '../../remoteImage'

class LineItem extends Component {
    ucFirst (string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    render () {
        let lineItem = this.props.lineItem
        let displayAttributes = [
            'color',
            'size',
        ]

        return (<div className={'o-layout c-cart__item c-cart__item--' + lineItem.type}>
            {this.props.showProductPicture ?
                <div className='c-cart__image o-layout__item u-1/2 u-1/3@lap u-1/4@desk'>
                    {lineItem.variant && lineItem.variant.images.length ?
                        <RemoteImage
                            url={lineItem.variant.images[0]}
                            alt={lineItem.name}
                            options={{ crop: 'pad', background: 'white' }}
                            cropRatio='2:1'
                        /> : null}
                </div> : null}
            <div className='o-layout__item u-1/2 u-1/3@lap u-1/2@desk'>
                <div className='c-cart__text'>
                    <h3 className='c-heading-gamma'>{lineItem.name}</h3>
                    {!lineItem.variant ? null : <ul>
                        {_.map(displayAttributes, (attribute) => {
                            if (!lineItem.variant.attributes[attribute]) {
                                return null
                            }

                            return (<li key={attribute}>
                                <strong>{this.ucFirst(attribute)}</strong>: {lineItem.variant.attributes[attribute]}
                            </li>)
                        })}
                    </ul>}
                    {lineItem.type === 'variant' ? <div className='c-button-row'>
                        <button
                            className='c-button c-button--small c-button--secondary'
                            onClick={(event) => {
                                app.getLoader('cart').removeLineItem({ lineItemId: lineItem.lineItemId })
                            }}
                        >
                            Remove
                        </button>
                        <button
                            className='c-button c-button--small c-button--secondary'
                        >
                            Add to Wishlist
                        </button>
                    </div> : null}
                </div>
            </div>
            <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk' align='right'>
                <div className='c-cart__price'>
                    {lineItem.type === 'variant' ?
                        <select
                            className='c-form__select'
                            value={lineItem.count}
                            onChange={(event) => {
                                app.getLoader('cart').updateLineItem({
                                    lineItemId: lineItem.lineItemId,
                                    count: +event.target.value,
                                })
                            }}
                        >
                            {_.map(_.range(1, 11), (count) => {
                                return <option value={count} key={count}>{count}</option>
                            })}
                        </select> : <div />}
                    <strong>{lineItem.isGift
                        ? 'Your gift!'
                        : <Fragment>
                            <span>
                                <Price value={lineItem.totalPrice} />
                            </span>
                            {lineItem.discountedPrice ?
                                <span>*</span>
                                : null}
                        </Fragment>
                    }</strong>
                </div>
            </div>
            {!lineItem.discountTexts.length ? null : <div className='o-layout__item u-1/1'>
                <ul className='c-discounts'>
                    {_.map(
                        lineItem.discountTexts,
                        (discountText, key) => {
                            // FIXME: Proper translation handling!
                            return <li className='c-discounts__item' key={key}>* {discountText[_.first(_.keys(discountText))]}</li>
                        }
                    )}
                </ul>
            </div>}
        </div>)
    }
}

LineItem.propTypes = {
    showProductPicture: PropTypes.bool,
    lineItem: PropTypes.object.isRequired,
}

LineItem.defaultProps = {
    showProductPicture: true,
}

export default LineItem
