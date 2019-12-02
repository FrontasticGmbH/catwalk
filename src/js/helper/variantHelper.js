import _ from 'lodash'

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

    return _.keys(_.pickBy(
        _.mapValues(attributeValues, _.uniq),
        (values) => {
            return values.length > 1
        }
    ))
}
