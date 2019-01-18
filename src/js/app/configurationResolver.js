import _ from 'lodash'

import { ConfigurationSchema } from 'frontastic-common'

/**
 * @param {ConfigurationSchema} schema
 * @param {object} streamData
 * @param {object} additionalData = {}
 */
const configurationResolver = function (schema, streamData, additionalData = {}) {
    return _.fromPairs(
        _.map(schema.fields, (fieldDefinition, fieldName) => {
            // Simple value handling
            let fieldValue = schema.get(fieldName)

            // Lookup stream data
            if (fieldDefinition.type === 'stream') {
                fieldValue = streamData[fieldValue] || null
            }

            // Lookup special field type values
            if (additionalData[fieldName]) {
                fieldValue = additionalData[fieldName]
            }

            return [fieldName, fieldValue]
        })
    )
}

export default configurationResolver
