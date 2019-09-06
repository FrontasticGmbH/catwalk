import _ from 'lodash'

/**
 * @param {Object} parameters
 * @param {boolean} isReadOnly
 * @constructor
 */
let ProductStreamParameters = function (parameters, isReadOnly = true) {
    this.parameters = parameters
    this.isReadOnly = isReadOnly

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
        this.assertWriteMode()
        if (!this.parameters.facets) {
            this.parameters.facets = {}
        }
        this.parameters.facets[filterHandle] = value
    }

    /**
     * @param {string} filterHandle
     */
    this.removeFilter = (filterHandle) => {
        this.assertWriteMode()
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
        this.assertWriteMode()
        if (offset === 0) {
            delete this.parameters.offset
        } else {
            this.parameters.offset = offset
        }
    }

    /**
     * @param {int} limit
     */
    this.setLimit = (limit = 0) => {
        this.assertWriteMode()
        if (limit === 0) {
            delete this.parameters.limit
        } else {
            this.parameters.limit = limit
        }
    }

    /**
     * @param {string} attributeId
     * @param {string} order
     */
    this.setSortOrder = (attributeId, order) => {
        this.assertWriteMode()
        if (!attributeId || !order) {
            delete this.parameters.sortAttributeId
            delete this.parameters.sortOrder
        } else {
            this.parameters.sortAttributeId = attributeId
            this.parameters.sortOrder = order
        }
    }

    this.assertWriteMode = () => {
        if (this.isReadOnly) {
            throw new Error('Parameters cannot be manipulated. Use UrlHandler.deriveParameters().')
        }
    }
}

export default ProductStreamParameters
