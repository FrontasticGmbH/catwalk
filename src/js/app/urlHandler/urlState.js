
import _ from 'lodash'
import ProductStreamParameters from './productStreamParameters'

const UrlState = function (parameters, streamConfigurations) {
    this.parameters = parameters
    this.streamMap = _.mapValues(
        _.keyBy(streamConfigurations, 'streamId'),
        (streamConfiguration) => {
            // @TODO: Make injectable
            return new ProductStreamParameters(
                (parameters.s || {})[streamConfiguration.streamId] || {},
                streamConfiguration
            )
        }
    )

    this.getStream = (streamId) => {
        if (!this.streamMap[streamId]) {
            throw new Error('Unknown stream with ID ' + streamId)
        }

        return this.streamMap[streamId]
    }
}

export default UrlState
