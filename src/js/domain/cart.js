import _ from 'lodash'

class Cart {
    constructor (cart = {}) {
        this.cartId = cart.cartId || null
        this.cartVersion = cart.cartVersion || 0
        this.lineItems = cart.lineItems || []
        this.sum = +cart.sum || 0
        this.payment = cart.payment || null
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
}

export default Cart
