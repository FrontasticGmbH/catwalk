
import _ from 'lodash'
import ProductStreamParameters from './productStreamParameters'
import ParameterHandlerFactory from './parameterHandlerFactory'

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
        let parameters = _.cloneDeep(this.parameters)

        parameters.s = _.mapValues(
            this.streamMap,
            /**
             *
             * @param {ProductStreamParameters} stream
             * @return {Object}
             */
            (stream) => {
                return stream.getParameters()
            }
        )

        const crawlable = _.reduce(
            _.map(this.streamMap, (stream) => {
                return stream.isCrawlable()
            }),
            (aggregateCrawlable, isCrawlable) => {
                return aggregateCrawlable && isCrawlable
            },
            true
        )

        if (crawlable) {
            delete parameters.nocrawl
        } else {
            parameters.nocrawl = '1'
        }

        return parameters
    }
}

export default UrlState
