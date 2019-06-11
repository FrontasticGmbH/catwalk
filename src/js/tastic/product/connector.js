/**
 * Returns the product in the `stream` field of the tastic and the selected variant + options, if available.
 *
 * @typedef ProductConnectorReturnValue
 * @property {object} product
 * @property {object} variant
 * @property {object} option Options currently selected for product
 * @property {number} selectedVariant
 *
 * @param globalState
 * @param props
 * @return {ProductConnectorReturnValue|{}}
 */
export default (globalState, props) => {
    let product = props.data.stream
    if (!product) {
        // eslint-disable-next-line no-console
        console.warn('No product could be found in streams')
        return { ...props }
    }

    let selectedVariant = globalState.app.route.get('_variant', 0)
    let variant = (product.variants && product.variants[selectedVariant]) || null
    if (!variant) {
        // eslint-disable-next-line no-console
        console.warn('Product variants are always required')
        return { ...props }
    }

    return {
        ...props,
        product: product,
        variant: variant,
        option: globalState.cart.productOptions[product.productId] || {},
        selectedVariant: +selectedVariant,
    }
}
