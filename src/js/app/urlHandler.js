import _ from 'lodash'

import Route from './route'
import UrlState from './urlHandler/urlState'
/**
 * @param {Route} route
 * @param {object} streamConfigurations
 * @constructor
 */
let UrlHandler = function (route, streamConfigurations) {
    this.route = route
    this.streamConfigurations = streamConfigurations

    /**
     * @param {manipulatorCallback} manipulator
     * @return {Object<string,*>}
     */
    this.deriveParameters = (manipulator) => {
        const urlState = new UrlState(
            _.cloneDeep(this.route.getParameters()),
            this.streamConfigurations
        )

        manipulator(urlState)

        return urlState.getParameters()
    }

}

export default UrlHandler

/**
 * @callback manipulatorCallback
 * @param {UrlState} urlState
 */
