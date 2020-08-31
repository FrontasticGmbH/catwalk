import Cart from '../../../src/js/domain/cart'
import cartFixture from './cartFixture.json'

const cart = new Cart(cartFixture)

it('returns the product count', () => {
    expect(cart.getProductCount()).toEqual(4)
})

it('returns the correct discount', () => {
    expect(cart.getDiscount()).toEqual(-22000)
})

it('returns the payed amount', () => {
    expect(cart.getPayedAmount()).toEqual(35000)
})

it('returns the remaining amount to pay', () => {
    expect(cart.getRemainingAmountToPay()).toEqual(0)
})

it('returns has user', () => {
    expect(cart.hasUser()).toEqual(true)
})

it('returns has shipping address', () => {
    expect(cart.hasShippingAddress()).toEqual(true)
})

it('returns has billing address', () => {
    expect(cart.hasBillingAddress()).toEqual(true)
})

it('returns has addresses', () => {
    expect(cart.hasAddresses()).toEqual(true)
})

it('returns has complete payments', () => {
    expect(cart.hasCompletePayments()).toEqual(true)
})

it('returns is complete', () => {
    expect(cart.isComplete()).toEqual(true)
})
