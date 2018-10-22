import Cart from './cart'

class Order extends Cart {
    constructor (order = {}) {
        super(order)

        this.orderId = order.orderId
    }
}

export default Order
