export default (globalState, props) => {
    let product = props.data.stream
    if (!product) {
        console.warn('No product could be found in streams')
        return { ...props }
    }

    let selectedVariant = globalState.app.route.get('_variant', 0)
    let variant = (product.variants && product.variants[selectedVariant]) || null
    if (!variant) {
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
