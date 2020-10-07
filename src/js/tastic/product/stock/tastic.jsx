//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { FormattedRelative, FormattedDate } from 'react-intl'

import productConnector from '../connector'
import icon from '../../../../layout/fastDelivery.svg'

class ProductStockTastic extends Component {
    ucFirst = (string) => {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    getNextOrderTime = () => {
        let date = new Date()
        date.setHours(17)
        date.setMinutes(0)

        if (date < new Date()) {
            date.setDate(date.getDate() + 1)
        }

        return date
    }

    getNextDeliveryDay = () => {
        let date = new Date()
        date.setDate(date.getDate() + 2)

        while (date.getDay() < 2) {
            date.setDate(date.getDate() + 2)
        }

        return date
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        if (!this.props.variant) {
            return null
        }

        if (this.props.variant.isOnStock) {
            return (<div className='o-layout o-layout-small'>
                <div className='o-layout__item u-1/4' align='right'>
                    <img src={icon} alt='Fast Delivery' />
                </div>
                <div className='o-layout__item u-3/4'>
                    <p>
                        <strong>Express Delivery</strong>: Order in the next <em>
                            <FormattedRelative value={this.getNextOrderTime()} />
                        </em> for a delivery until <em>
                            <FormattedDate value={this.getNextDeliveryDay()} day='numeric' month='long' />
                        </em>.
                    </p>
                </div>
            </div>)
        } else {
            return <p>Variant sold out right now.</p>
        }
    }
}

ProductStockTastic.propTypes = {
    variant: PropTypes.object,
}

ProductStockTastic.defaultProps = {
    variant: null,
}

export default connect(productConnector)(ProductStockTastic)
