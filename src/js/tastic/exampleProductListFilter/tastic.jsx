import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import _ from 'lodash'
import Entity from '../../app/entity'
import UrlHandler from '../../app/urlHandler'

import app from '../../app/app'
import facetConnector from '../../app/connector/facet'
import categoryConnector from '../../app/connector/category'
import urlHandlerConnector from '../../app/connector/urlHandler'

class ExampleProductListFilterTastic extends Component {

    render () {
        let productList = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!productList || !productList.facets || productList.facets.length === 0) {
            // eslint-disable-next-line no-console
            console.warn('Product list does not contain facets. Won\'t display filter tastic.')
            return null
        }

        if (!this.props.facets.isComplete()) {
            // eslint-disable-next-line no-console
            console.log('Facet configuration not loaded. Will not render facets.')
            return null
        }

        return (<Fragment>
            <p><strong>I am the selector!!!!1elf</strong></p>
        </Fragment>)
    }

    getFacetValues = (streamId) => {
        if (!this.props.urlHandler) {
            return {}
        }
        return this.props.urlHandler.parameterReader(streamId).getParameters().facets || {}
    }

    selectFacetValue = (facet, value) => {
        const parameters = this.props.urlHandler.deriveParameters((urlState) => {
            urlState.getStream(this.props.tastic.configuration.stream).setFilter(facet.handle, value)
        })

        app.getRouter().push(this.props.route.route, parameters)
    }

    removeFacetValue = (facet) => {
        const parameters = this.props.urlHandler.deriveParameters((urlState) => {
            urlState.getStream(this.props.tastic.configuration.stream).removeFilter(facet.handle)
        })

        app.getRouter().push(this.props.route.route, parameters)
    }

    filterCategoryTerms = (terms) => {
        return _.uniqBy(terms, 'name')
    }
}

ExampleProductListFilterTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,

    // From connector
    route: PropTypes.object.isRequired,

    // From urlHandlerConnector
    urlHandler: PropTypes.instanceOf(UrlHandler),

    // From facetConnector
    facets: PropTypes.instanceOf(Entity).isRequired,
}

export default compose(
    connect(facetConnector),
    connect(urlHandlerConnector),
    connect((globalState) => {
        let streamParameters = globalState.app.route.parameters.s || {}

        return {
            route: globalState.app.route,
            streamParameters: streamParameters,
        }
    }),
)(ExampleProductListFilterTastic)
