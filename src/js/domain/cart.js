import _ from 'lodash'

class Cart {
    constructor (cart = {}) {
        this.cartId = cart.cartId || null
        this.cartVersion = cart.cartVersion || 0
        this.lineItems = cart.lineItems || []
        this.sum = +cart.sum || 0
        this.email = cart.email || null
        this.birthday = cart.birthday || null
        this.shippingMethod = cart.shippingMethod || null
        this.shippingAddress = cart.shippingAddress || null
        this.billingAddress = cart.billingAddress || null
        this.payments = cart.payments || []
    }

    getLineItems () {
        return this.lineItems
    }

    getProductCount () {
        return _.sumBy(
            _.filter(
                this.lineItems,
                (lineItem) => {
                    return !!lineItem.variant
                }
            ),
            'count'
        )
    }

    getDiscount () {
        return _.sum(this.lineItems.map((lineItem) => {
            return (lineItem.price * lineItem.count) - lineItem.totalPrice
        }))
    }

    getPayedAmount () {
        return _.sum(this.payments.map((payment) => { return payment.amount }))
    }

    getRemainingAmountToPay () {
        return this.sum - this.getPayedAmount()
    }

    hasUser () {
        return !!this.email
    }

    hasShippingAddress () {
        return (
            !!this.shippingAddress &&
            !!this.shippingAddress.salutation &&
            !!this.shippingAddress.firstName &&
            !!this.shippingAddress.lastName &&
            !!this.shippingAddress.postalCode &&
            !!this.shippingAddress.city &&
            !!this.shippingAddress.country
        )
    }

    hasBillingAddress () {
        return (
            !!this.billingAddress &&
            !!this.billingAddress.salutation &&
            !!this.billingAddress.firstName &&
            !!this.billingAddress.lastName &&
            !!this.billingAddress.postalCode &&
            !!this.billingAddress.city &&
            !!this.billingAddress.country
        )
    }
    
    hasAddresses () {
        return (
            this.hasShippingAddress() &&
            this.hasBillingAddress()
        )
    }

    hasCompletePayments () {
        return (
            !!this.payments.length &&
            (this.getPayedAmount() >= this.sum)
        )
    }

    isComplete () {
        return this.hasUser() && this.hasAddresses() && this.hasCompletePayments()
    }
}

export default Cart
