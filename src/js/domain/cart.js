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
}

export default Cart
