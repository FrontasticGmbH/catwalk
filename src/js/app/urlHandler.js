import _ from 'lodash'

import UrlState from './urlHandler/urlState'
import ParameterHandlerFactory from './urlHandler/parameterHandlerFactory'

/* eslint-disable no-unused-vars */
// JSDoc imports
import Route from './route'
import ProductStreamParameters from './urlHandler/productStreamParameters'
/* eslint-enable no-unused-vars */

/**
 * @param {Route} route
 * @param {Object<string, *>} streamConfigurations
 * @constructor
 */
let UrlHandler = function (route, streamConfigurations) {
    this.route = route
    this.streamConfigurations = streamConfigurations
    this.parameterReaderFactory = new ParameterHandlerFactory(streamConfigurations)

    /**
     * @param {manipulatorCallback} manipulator
     * @return {Object<string,*>}
     */
    this.deriveParameters = (manipulator) => {
        const urlState = new UrlState(
            _.extend({}, this.route.query, this.route.parameters),
            new ParameterHandlerFactory(this.streamConfigurations, false)
        )

        manipulator(urlState)

        return urlState.getParameters()
    }

    /**
     * @param {string} streamId
     * @return {ProductStreamParameters}
     */
    this.parameterReader = (streamId) => {
        return this.parameterReaderFactory.createParameterHandler(streamId, this.route.query)
    }
}

export default UrlHandler

/**
 * @callback manipulatorCallback
 * @param {UrlState} urlState
 */
