import { generateId } from 'frontastic-common'
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import Spinner from '../../../layout/loading.svg'

import app from '../../app/app'
import Entity from '../../app/entity'

import Summary from '../cart/summary'
import Email from './email'
import Address from './address'
import Paypal from './paypal'

class Checkout extends Component {
    constructor (props) {
        super(props)

        this.state = {
            checkingOut: false,
            differentBillingAddress: false,
            user: {},
            shipping: {},
            billing: null,
            payment: null,
        }
    }

    isComplete = () => {
        return !!this.state.user.email &&
            !!this.state.shipping.firstName &&
            !!this.state.shipping.lastName &&
            !!this.state.shipping.streetName &&
            !!this.state.shipping.streetNumber &&
            !!this.state.shipping.postalCode &&
            !!this.state.shipping.city &&
            !!this.state.payment
    }

    render () {
        return (<div className='c-checkout o-layout'>
            <div className='c-cart__items o-layout__item u-1/1 u-3/4@lap u-3/4@desk'>
                <h2 className='c-heading-beta'>Bestelldaten</h2>
                <h3 className='c-heading-gamma'>E-Mail-Adresse</h3>
                <Email onChange={(state) => { this.setState({ user: state }) }} />
                <h3 className='c-heading-gamma'>Lieferadresse</h3>
                <Address onChange={(state) => { this.setState({ shipping: state }) }} />
                <h3 className='c-heading-gamma'>Rechnunsgadresse</h3>
                <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                    <label className='c-form__label-checkbox'>
                        Abweichende Rechnungsadresse
                        <input
                            className='c-form__input'
                            type='checkbox'
                            checked={this.state.differentBillingAddress}
                            onChange={(event) => {
                                this.setState({ differentBillingAddress: event.target.checked })
                            }}
                        />
                    </label>
                </div>
                {!this.state.differentBillingAddress ? null :
                <Address onChange={(state) => { this.setState({ billing: state }) }} scope='billing' />}

                <h3 className='c-heading-gamma'>Zahlungsmethode</h3>
                {this.props.cart && this.props.cart.isComplete() ?
                    <Fragment>
                        {this.props.data.paypalSandboxClientId && this.props.data.paypalProductionClientId ?
                            <div className='c-form__item o-layout__item u-1/2 u-1/2@lap'>
                                <Paypal
                                    sandboxClientId={this.props.data.paypalSandboxClientId}
                                    productionClientId={this.props.data.paypalProductionClientId}
                                    cart={this.props.cart.data}
                                    onPaymentSuccess={(payment) => {
                                    this.setState({
                                        payment: payment,
                                    })
                                }}
                                    payment={this.state.payment}
                            />
                            </div> : null}
                        <div className='c-form__item o-layout__item u-1/2 u-1/2@lap'>
                            <button
                                className={'c-button c-button--full' + (this.state.payment && this.state.payment.provider === 'advance-payment' ? ' c-button--primary' : '')}
                                onClick={() => {
                                    this.setState({
                                        payment: {
                                            provider: 'advance-payment',
                                            id: generateId(),
                                        },
                                    })
                                }}
                            >
                                Vorkasse
                            </button>
                        </div>
                    </Fragment>
                : null}
            </div>
            <div className='o-layout__item u-1/1 u-1/4@lap u-1/4@desk'>
                {this.props.cart.data ? <Summary cart={this.props.cart.data} /> : null}
                {!this.state.payment ? <div className='c-alert c-alert-info'>
                    <p>Please select a payment method</p>
                </div> : null}
                <button
                    className='c-button c-button--full c-button--primary'
                    disabled={!this.isComplete() || this.state.checkingOut}
                    onClick={() => {
                        this.setState({ checkingOut: true })
                        app.getLoader('cart').checkout({
                            user: this.state.user,
                            shipping: this.state.shipping,
                            billing: this.state.billing,
                            payment: this.state.payment,
                        })
                    }}
                >
                    {this.state.checkingOut ?
                        <Fragment>
                            <img src={Spinner} width={32} height={32} alt='Loading' />
                            In Bearbeitung
                        </Fragment> :
                        'Kostenpflichtig Bestellen'}
                </button>
            </div>
        </div>)
    }
}

Checkout.propTypes = {
    cart: PropTypes.instanceOf(Entity).isRequired,
    data: PropTypes.object.isRequired,
}

Checkout.defaultProps = {}

export default connect(
    (globalState, props) => {
        return {
            cart: globalState.cart.cart || new Entity(),
        }
    }
)(Checkout)
