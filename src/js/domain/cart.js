class Cart {
    constructor (cart = {}) {
        this.cartId = cart.cartId || null
        this.cartVersion = cart.cartVersion || 0
        this.projectSpecificData = cart.projectSpecificData || {}
        this.discountCodes = cart.discountCodes || []
        this.lineItems = cart.lineItems || []
        this.sum = +cart.sum || 0
        this.currency = cart.currency || null
        this.email = cart.email || null
        this.birthday = cart.birthday || null
        this.shippingMethod = cart.shippingMethod || null
        this.shippingAddress = cart.shippingAddress || null
        this.billingAddress = cart.billingAddress || null
        this.payments = cart.payments || []
        this.projectSpecificData = cart.projectSpecificData || {}
    }

    getLineItems () {
        return this.lineItems
    }

    getProductCount () {
        let productCount = 0
        for (let lineItem of this.lineItems) {
            if (!lineItem.variant) {
                continue
            }

            productCount += lineItem.count
        }

        return productCount
    }

    getDiscount () {
        let discount = 0
        for (let lineItem of this.lineItems) {
            discount += (lineItem.discountedPrice || lineItem.price) - lineItem.totalPrice
        }
        return discount
    }

    getPayedAmount () {
        let payed = 0
        for (let payment of this.payments) {
            if (payment.paymentStatus !== 'paid') {
                continue
            }

            payed += payment.amount
        }

        return payed
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
        let paymentPaid = false
        this.payments.forEach(function (payment) {
            if (payment.paymentStatus === 'paid') {
                paymentPaid = true
            }
        })

        return (
            paymentPaid &&
            (this.getPayedAmount() >= this.sum)
        )
    }

    isComplete () {
        return this.hasUser() && this.hasAddresses() && this.hasCompletePayments()
    }
}

export default Cart
