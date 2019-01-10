class Wishlist {
    constructor (wishlist = {}) {
        this.wishlistId = wishlist.wishlistId || null
        this.lineItems = wishlist.lineItems || []
    }

    getLineItems () {
        return this.lineItems
    }
}

export default Wishlist
