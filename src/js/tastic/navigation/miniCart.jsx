//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import iconBag from '../../../icons/icomoon_icons/SVG/bag.svg'
import app from '../../app/app'
import Cart from '../../domain/cart'

class MiniCart extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        let count = this.props.cart.getProductCount()

        return (<button
            className='c-button c-navbar__button c-cart-link'
            aria-label='Mein Warenkorb'
            onClick={() => {
                app.getRouter().push('Frontastic.Frontend.Master.Checkout.cart')
            }} >
            <img className='o-icon' data-icon='cart' data-icon-size='base' src={iconBag} alt='Cart' />
            <span className={'c-cart-link__badge c-badge c-badge--tiny' + (count ? '' : ' c-badge--invert')}>
                {count}
            </span>
        </button>)
    }
}

MiniCart.propTypes = {
    cart: PropTypes.instanceOf(Cart).isRequired,
}

MiniCart.defaultProps = {}

export default connect(
    (globalState, props) => {
        return {
            cart: (globalState.cart.cart && globalState.cart.cart.data) || new Cart(),
        }
    }
)(MiniCart)
