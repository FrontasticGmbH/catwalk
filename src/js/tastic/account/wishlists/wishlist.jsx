//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import app from '../../../app/app'

import AtomsHeading from '../../../patterns/atoms/headings/heading'
import MoleculesLineItem from '../../../patterns/molecules/cart/line-item'

class Wishlist extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<div className='c-wishlist o-layout'>
            <div className='o-layout__item u-1/1'>
                <AtomsHeading type='beta'>{this.props.wishlist.name}</AtomsHeading>
            </div>
            {_.map(this.props.wishlist.lineItems, (lineItem) => {
                return <MoleculesLineItem
                    key={lineItem.lineItemId}
                    lineItem={lineItem}
                    onRemove={(lineItem) => {
                        app.getLoader('wishlist').removeLineItem(this.props.wishlist.wishlistId, {
                            lineItemId: lineItem.lineItemId,
                        })
                    }}
                    onChangeCount={(lineItem, count) => {
                        app.getLoader('wishlist').updateLineItem(this.props.wishlist.wishlistId, {
                            lineItemId: lineItem.lineItemId,
                            count: count,
                        })
                    }}
                />
            })}
        </div>)
    }
}

Wishlist.propTypes = {
    wishlist: PropTypes.object.isRequired,
}

Wishlist.defaultProps = {
}

export default Wishlist
