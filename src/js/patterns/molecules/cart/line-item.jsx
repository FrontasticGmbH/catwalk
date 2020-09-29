//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'
import AtomsHeading from '../../atoms/headings/heading'
import AtomsPrice from '../../atoms/prices/price'
import RemoteImage from '../../../remoteImage'

class MoleculesLineItem extends Component {
    ucFirst (string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
                    <AtomsHeading type='gamma'>{lineItem.name}</AtomsHeading>
                    {!lineItem.variant ? null : <ul>
                        {_.map(displayAttributes, (attribute) => {
                            if (!lineItem.variant.attributes[attribute]) {
                                return null
                            }

                            return (<li key={attribute}>
                                <strong>{this.ucFirst(attribute)}</strong>: {lineItem.variant.attributes[attribute].label || lineItem.variant.attributes[attribute]}
                            </li>)
                        })}
                    </ul>}
                    {lineItem.type === 'variant' && (this.props.onRemove || this.props.onAddToWishlist) ? <div className='c-button-row'>
                        {this.props.onRemove ? <AtomsButton
                            size='small'
                            type='secondary'
                            onClick={(event) => {
                                this.props.onRemove(lineItem)
                            }}
                        >
                            Remove
                        </AtomsButton> : null}
                        {this.props.onAddToWishlist ? <AtomsButton
                            size='small'
                            type='secondary'
                            onClick={(event) => {
                                this.props.onAddToWishlist(lineItem, +event.target.value)
                            }}
                        >
                            Add to Wishlist
                        </AtomsButton> : null}
                    </div> : null}
                </div>
            </div>
            <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk' align='right'>
                <div className='c-cart__price'>
                    {lineItem.type === 'variant' && this.props.onChangeCount ?
                        <select
                            className='c-form__select'
                            value={lineItem.count}
                            onChange={(event) => {
                                this.props.onChangeCount(lineItem, +event.target.value)
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
                                <AtomsPrice value={lineItem.totalPrice || (lineItem.count * lineItem.variant.price) || 0} />
                            </span>
                            {lineItem.discountedPrice ? <span>*</span> : null}
                        </Fragment>
                    }</strong>
                </div>
            </div>
            {!lineItem.discountTexts || !lineItem.discountTexts.length ? null : <div className='o-layout__item u-1/1'>
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

MoleculesLineItem.propTypes = {
    lineItem: PropTypes.object.isRequired,
    showProductPicture: PropTypes.bool,
    onRemove: PropTypes.func,
    onChangeCount: PropTypes.func,
    onAddToWishlist: PropTypes.func,
}

MoleculesLineItem.defaultProps = {
    showProductPicture: true,
    onRemove: null,
    onChangeCount: null,
    onAddToWishlist: null,
}

export default ComponentInjector.return('MoleculesLineItem', MoleculesLineItem)
