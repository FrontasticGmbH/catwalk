//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { FormattedMessage } from 'react-intl'
import _ from 'lodash'

import AtomsHeading from '../../../patterns/atoms/headings/heading'
import MoleculesLineItem from '../../../patterns/molecules/cart/line-item'

import AccountLoginForm from '../login/form'
import AccountBar from '../bar'
import Summary from '../../cart/summary'
import Order from '../../../domain/order'

class AccountOrdersTastic extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        if (!this.props.context.session.loggedIn) {
            return <AccountLoginForm />
        }

        let orders = this.props.rawData.stream.__master
        return (
            <div className='o-layout'>
                <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                    <AccountBar selected='orders' />
                </div>
                <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                    <AtomsHeading type='alpha'>
                        <FormattedMessage id={'account.orders'} />
                    </AtomsHeading>
                    {_.map(orders, (order) => {
                        order = new Order(order)

                        return (
                            <div className='c-order o-layout' key={order.orderId}>
                                <div className='c-order__items o-layout__item u-1/1 u-3/4@lap u-3/4@desk'>
                                    <h2 className='c-heading-beta'>
                                        Ihre Bestellung ({order.orderId}, {order.getProductCount()} Artikel)
                                    </h2>
                                    {!order.lineItems.length ? (
                                        <p className='c-order__message'>Nothing in here…</p>
                                    ) : (
                                        _.map(order.lineItems, (lineItem) => {
                                            return <MoleculesLineItem key={lineItem.lineItemId} lineItem={lineItem} />
                                        })
                                    )}
                                </div>
                                <div className='o-layout__item u-1/1 u-1/4@lap u-1/4@desk'>
                                    {order ? <Summary cart={order} /> : null}
                                </div>
                            </div>
                        )
                    })}
                </div>
            </div>
        )
    }
}

AccountOrdersTastic.propTypes = {
    context: PropTypes.object.isRequired,
    rawData: PropTypes.object,
}

AccountOrdersTastic.defaultProps = {}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
    }
})(AccountOrdersTastic)
