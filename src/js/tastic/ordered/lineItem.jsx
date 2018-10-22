import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

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
                </div>
            </div>
            <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk' align='right'>
                <div className='c-cart__price'>
                    <strong><Price value={lineItem.totalPrice} /></strong>
                </div>
            </div>
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
