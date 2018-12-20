
/**
 * @param {Object} parameters
 * @param {Object} streamConfiguration
 * @constructor
 */
let ProductStreamParameters = function (parameters, streamConfiguration) {
    this.parameters = parameters

    /**
     * @return {Object}
     */
    this.getParameters = () => {
        return this.parameters
    }

    /**
     * @return {bool}
     */
    this.isCrawlable = () => {

    }

    /**
     * @param {string} filterHandle
     * @param {*} value
     */
    this.setFilterValue = (filterHandle, value) => {

    }

    /**
     * @param {string} filterHandle
     */
    this.removeFilter = (filterHandle) => {

    }

    /**
     * @param {int} offset
     */
    this.setOffset = (offset = 0) => {

    }
}

export default ProductStreamParameters
