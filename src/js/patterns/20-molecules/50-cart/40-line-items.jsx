import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import fixture from '../../fixture'

import MoleculesLineItem from './30-line-item'

class MoleculesLineItems extends Component {
    render () {
        return (<Fragment>
            <MoleculesLineItem
                lineItem={{
                    lineItemId: 'test-full',
                    name: 'Full featured line item',
                    type: 'variant',
                    variant: fixture.product.variants[0],
                    count: 1,
                    price: 19999,
                    discountedPrice: 17999,
                    discountTexts: [
                        { en: 'Reduced because of demo!' },
                    ],
                    totalPrice: 17999,
                    isGift: false,
                }}
                onRemove={() => {}}
                onChangeCount={() => {}}
                onAddToWishlist={() => {}}
            />
            <MoleculesLineItem
                lineItem={{
                    lineItemId: 'test',
                    name: 'Two gifts without interactions',
                    type: 'variant',
                    variant: fixture.product.variants[0],
                    count: 2,
                    price: 19999,
                    totalPrice: 2 * 19999,
                    isGift: true,
                }}
            />
            <MoleculesLineItem
                lineItem={{
                    lineItemId: 'test',
                    name: 'No variant',
                    type: 'option',
                    count: 1,
                    price: 1299,
                    totalPrice: 1299,
                    isGift: false,
                }}
            />
            <MoleculesLineItem
                showProductPicture={false}
                lineItem={{
                    lineItemId: 'test-full',
                    name: 'Full featured line item',
                    type: 'variant',
                    variant: fixture.product.variants[0],
                    count: 1,
                    price: 19999,
                    discountedPrice: 17999,
                    discountTexts: [
                        { en: 'Reduced because of demo!' },
                    ],
                    totalPrice: 17999,
                    isGift: false,
                }}
                onRemove={() => {}}
                onChangeCount={() => {}}
                onAddToWishlist={() => {}}
            />
            <MoleculesLineItem
                showProductPicture={false}
                lineItem={{
                    lineItemId: 'test',
                    name: 'Two gifts without interactions',
                    type: 'variant',
                    variant: fixture.product.variants[0],
                    count: 2,
                    price: 19999,
                    totalPrice: 2 * 19999,
                    isGift: true,
                }}
            />
            <MoleculesLineItem
                showProductPicture={false}
                lineItem={{
                    lineItemId: 'test',
                    name: 'No variant',
                    type: 'option',
                    count: 1,
                    price: 1299,
                    totalPrice: 1299,
                    isGift: false,
                }}
            />
        </Fragment>)
    }
}

MoleculesLineItems.propTypes = {
}

MoleculesLineItems.defaultProps = {
}

// These are just default props for the pattern library
MoleculesLineItems.testProps = {
}

export default MoleculesLineItems
