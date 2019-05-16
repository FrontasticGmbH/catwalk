import Cart from './cart'

class Order extends Cart {
    constructor (order = {}) {
        super(order)

        this.orderId = order.orderId
        this.orderState = order.orderState || null
        this.createdAt = order.createdAt || null
    }
}

export default Order
