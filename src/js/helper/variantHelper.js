export const getLabelFromAttribute = (variantAttributes, attributeName) => {
    if (variantAttributes[attributeName]) {
        return variantAttributes[attributeName].label || variantAttributes[attributeName]
    }
    return variantAttributes[attributeName]
}

export const getVariantAttributes = (productVariants) => {
    let attributeValues = {}
    for (let variant of productVariants) {
        for (let attribute in variant.attributes) {
            if (!attributeValues[attribute]) {
                attributeValues[attribute] = []
            }

            attributeValues[attribute].push(
                getLabelFromAttribute(variant.attributes, attribute)
            )
        }
    }

    let attributesWithMultipleValues = []
    for (let [attribute, values] of Object.entries(attributeValues)) {
        if ((new Set(values)).size > 1) {
            attributesWithMultipleValues.push(attribute)
        }
    }

    return attributesWithMultipleValues
}
