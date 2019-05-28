import React, { Component, Fragment } from 'react'
import ReactDOM from 'react-dom'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import paypal from 'paypal-checkout'
import Cart from '../../domain/cart'
import Context from '../../app/context'

class Paypal extends Component {
    render () {
        if (!this.props.sandboxClientId || !this.props.productionClientId) {
            return null
        }

        const client = {
            sandbox: this.props.sandboxClientId,
            production: 'AZwA1Lxqyrqp7WzOXpxt3To0R6x8IoNkxiGz1BGaUDEBz3ZabyNBoA5tIUz1oP3LGbop0aQfKhtrQ06u',
        }

        const PaypalButton = paypal.Button.driver('react', { React, ReactDOM })

        return (<Fragment>
            <PaypalButton
                env='sandbox'
                client={client}
                payment={this.payment}
                onAuthorize={this.onAuthorize}
                commit={false}
                style={{
                    size: 'medium',
                    color: 'silver',
                }}
            />

            {(this.props.payment && this.props.payment.provider === 'paypal-express')
                ? <p className='c-alert c-alert--info'>Bezahlt</p>
                : null}

        </Fragment>)
    }

    payment = (data, actions) => {
        return actions.payment.create({
            transactions: [
                {
                    amount: {
                        total: this.props.cart.sum / 100,
                        currency: this.props.context.currency,
                    },
                },
            ],
        })
    }

    onAuthorize = (data, actions) => {
        return actions.payment.execute().then((response) => {
            this.props.onPaymentSuccess({
                provider: 'paypal-express',
                id: response.id,
            })
        })
    }
}

Paypal.propTypes = {
    sandboxClientId: PropTypes.string,
    productionClientId: PropTypes.string,
    cart: PropTypes.instanceOf(Cart).isRequired,
    onPaymentSuccess: PropTypes.func.isRequired,
    context: PropTypes.instanceOf(Context).isRequired,
    payment: PropTypes.object,
}

Paypal.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(Paypal)
