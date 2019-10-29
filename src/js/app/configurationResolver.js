import _ from 'lodash'

/**
 * @param {ConfigurationSchema} schema
 * @param {object} streamData
 * @param {object} additionalData = {}
 */
const configurationResolver = function (schema, streamData, additionalData = {}) {
    return schema.getConfigurationWithResolvedStreams(streamData, additionalData)
}

export default configurationResolver
