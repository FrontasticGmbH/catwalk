import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import _ from 'lodash'

import Entity from '../../app/entity'

import Summary from '../cart/summary'
import MoleculesLineItem from '../../patterns/molecules/cart/line-item'
import Order from '../../domain/order'

class Ordered extends Component {
    render () {
        let order = null
        if (this.props.order.data) {
            order = new Order(this.props.order.data)
        }

        return (<div className='c-order o-layout'>
            {!order ? null : (<Fragment>
                <div className='c-order__items o-layout__item u-1/1 u-3/4@lap u-3/4@desk'>
                    <h2 className='c-heading-beta'>Ihre Bestellung ({order.orderId}, {order.getProductCount()} Artikel)</h2>
                    {!order.lineItems.length ?
                        <p className='c-order__message'>Nothing in here…</p> :
                        _.map(order.lineItems, (lineItem) => {
                            return <MoleculesLineItem key={lineItem.lineItemId} lineItem={lineItem} />
                        })
                    }
                </div>
                <div className='o-layout__item u-1/1 u-1/4@lap u-1/4@desk'>
                    {order ? <Summary cart={order} /> : null}
                </div>
            </Fragment>)}
        </div>)
    }
}

Ordered.propTypes = {
    order: PropTypes.instanceOf(Entity).isRequired,
}

Ordered.defaultProps = {}

export default connect(
    (globalState, props) => {
        let order = null

        if (globalState.app.route.has('order')) {
            order = globalState.cart.orders[globalState.app.route.get('order')] || null
        } else {
            order = globalState.cart.lastOrder
        }

        return {
            order: order || new Entity(),
        }
    }
)(Ordered)
