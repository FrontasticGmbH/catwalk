//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Cart from '../../domain/cart'
import Price from '../../patterns/atoms/prices/price'

class Summary extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        let cart = this.props.cart

        return (<Fragment>
            <h2 className='c-heading-beta'>Gesammtsumme</h2>
            <table className='c-cart__summary o-table'>
                <tbody>
                    <tr>
                        <td>Zwischensumme</td>
                        <td align='right'><Price value={cart.sum} /></td>
                    </tr>
                    <tr>
                        <td>Versandkosten</td>
                        <td align='right'><Price value={0} /></td>
                    </tr>
                    <tr>
                        <th align='left'>Gesammtsumme</th>
                        <th align='right'><Price value={cart.sum} /></th>
                    </tr>
                </tbody>
            </table>
        </Fragment>)
    }
}

Summary.propTypes = {
    cart: PropTypes.instanceOf(Cart).isRequired,
}

Summary.defaultProps = {
}

export default Summary
