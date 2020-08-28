/* eslint-disable no-unused-vars */
// JSDoc imports
import ProductStreamParameters from './productStreamParameters'
import ParameterHandlerFactory from './parameterHandlerFactory'
/* eslint-enable no-unused-vars */

/**
 * @param {Object<string, *>} parameters
 * @param {ParameterHandlerFactory} parameterHandlerFactory
 * @constructor
 */
const UrlState = function (parameters, parameterHandlerFactory) {
    /**
     * @private
     * @type {Object}
     */
    this.parameters = parameters
    /**
     * @private
     * @type {Object<string, ProductStreamParameters>}
     */
    this.streamMap = parameterHandlerFactory.createParameterHandlerMap(parameters)

    /**
     * @param {string} streamId
     * @return {ProductStreamParameters}
     */
    this.getStream = (streamId) => {
        if (!this.streamMap[streamId]) {
            throw new Error('Unknown stream with ID ' + streamId)
        }

        return this.streamMap[streamId]
    }

    /**
     * @return {Object}
     */
    this.getParameters = () => {
        let parameters = { s: {}, ...this.parameters }
        let crawlable = true
        for (let [streamId, stream] of Object.entries(this.streamMap)) {
            let streamParameters = stream.getParameters()
            if (Object.keys(streamParameters).length > 0) {
                parameters.s[streamId] = streamParameters
            }

            crawlable = crawlable && stream.isCrawlable()
        }

        if (crawlable) {
            delete parameters.nocrawl
        } else {
            parameters.nocrawl = '1'
        }

        return parameters
    }
}

export default UrlState
