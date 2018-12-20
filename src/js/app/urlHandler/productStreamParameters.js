import _ from 'lodash'

/**
 * @param {Object} parameters
 * @param {Object} streamConfiguration
 * @constructor
 */
let ProductStreamParameters = function (parameters) {
    this.parameters = parameters

    /**
     * @return {Object}
     */
    this.getParameters = () => {
        return this.parameters
    }

    /**
     * @return {boolean}
     */
    this.isCrawlable = () => {
        return (!this.parameters.facets)
    }

    /**
     * @param {string} filterHandle
     * @param {*} value
     */
    this.setFilter = (filterHandle, value) => {
        if (!this.parameters.facets) {
            this.parameters.facets = {}
        }
        this.parameters.facets[filterHandle] = value
    }

    /**
     * @param {string} filterHandle
     */
    this.removeFilter = (filterHandle) => {
        if (!this.parameters.facets) {
            return
        }

        delete this.parameters.facets[filterHandle]

        if (_.isEmpty(this.parameters.facets)) {
            delete this.parameters.facets
        }
    }

    /**
     * @param {int} offset
     */
    this.setOffset = (offset = 0) => {
        if (offset === 0) {
            delete this.parameters.offset
        } else {
            this.parameters.offset = offset
        }
    }
}

export default ProductStreamParameters
