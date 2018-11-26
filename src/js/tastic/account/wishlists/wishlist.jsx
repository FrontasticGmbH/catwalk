import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import app from '../../../app/app'

import AtomsButton from '../../../patterns/atoms/buttons/button'
import AtomsHeading from '../../../patterns/atoms/headings/heading'
import MoleculesLineItem from '../../../patterns/molecules/cart/line-item'

class Wishlist extends Component {
    render () {
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
                            lineItemId: lineItem.lineItemId
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
