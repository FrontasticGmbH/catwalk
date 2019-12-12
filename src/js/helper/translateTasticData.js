import getTranslation from '../getTranslation'

/**
 * Converts tastic data (which is usually received in props.data) so that translatable objects will be replaced with strings
 *
 * @param {object} data         tastic fields
 * @param {object} tasticSchema the schema of the tastic
 * @param {object} context      the frontastic context object
 */
export const translateTasticData = (data, tasticSchema, context) => {
    const translatedData = {
        ...data,
    }

    Object.keys(tasticSchema.fields).forEach(fieldName => {
        const field = tasticSchema.fields[fieldName]
        if (isTranslatableString(field)) {
            translatedData[fieldName] = getTranslation(
                data[fieldName],
                context.locale,
                context.project.defaultLanguage
            ).text
        }
        if (field.type === 'group') {
            translatedData[fieldName] = translateTasticGroupField(data[fieldName], field, context)
        }
    })

    return translatedData
}

const translateTasticGroupField = (groupData, groupSchema, context) => {
    // group fields might be unintialized, ignore them in that case
    if (!Array.isArray(groupData)) {
        return groupData
    }
    return groupData.map(groupElement => {
        const translatedGroupElement = {
            ...groupElement,
        }

        groupSchema.fields.forEach(fieldSchema => {
            if (isTranslatableString(fieldSchema)) {
                translatedGroupElement[fieldSchema.field] = getTranslation(
                    groupElement[fieldSchema.field],
                    context.locale,
                    context.project.defaultLanguage
                ).text
            }

            if (fieldSchema.type === 'group') {
                translatedGroupElement[fieldSchema.field] = translateTasticGroupField(groupElement[fieldSchema.field], fieldSchema, context)
            }
        })

        return translatedGroupElement
    })
}

const isTranslatableString = (fieldSchema) => {
    if (typeof fieldSchema.translatable !== 'undefined') {
        return fieldSchema.translatable
    }

    if (!['string', 'text'].includes(fieldSchema.type)) {
        return false
    }

    return false
}
