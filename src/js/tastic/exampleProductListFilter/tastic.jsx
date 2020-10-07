//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import _ from 'lodash'
import Entity from '../../app/entity'
import UrlHandler from '../../app/urlHandler'

import app from '../../app/app'
import facetConnector from '../../app/connector/facet'
import urlHandlerConnector from '../../app/connector/urlHandler'

class ExampleProductListFilterTastic extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        const productList = this.props.data.stream

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

        const colorFacet = _.find(productList.facets, { handle: 'variants.attributes.color' })
        if (!colorFacet) {
            // eslint-disable-next-line no-console
            console.error('Missing facet variants.attributes.color from the example dataset. Please activate it in Backstage!')
            return null
        }

        const sizeFacet = _.find(productList.facets, { handle: 'variants.attributes.size' })
        if (!sizeFacet) {
            // eslint-disable-next-line no-console
            console.error('Missing facet variants.attributes.size from the example dataset. Please activate it in Backstage!')
            return null
        }

        const streamId = this.props.tastic.configuration.stream

        return (<Fragment>
            <div style={{ width: '50%', 'float': 'left' }}>
                <h1>Color Filter:</h1>
                <ul>
                    {colorFacet.terms.map((term) => {
                    return this.renderTerm(term, (event) => {
                        event.preventDefault()
                        this.toggleFacetTerm(streamId, colorFacet, term)
                    })
                })}
                </ul>
            </div>
            <div style={{ width: '50%', 'float': 'left' }}>
                <h1>Size Filter:</h1>
                <ul>
                    {sizeFacet.terms.map((term) => {
                    return this.renderTerm(term, (event) => {
                        event.preventDefault()
                        this.toggleFacetTerm(streamId, sizeFacet, term)
                    })
                })}
                </ul>
            </div>
        </Fragment>)
    }

    renderTerm = (term, toggleCallback) => {
        return (<li>
            <a href='#' onClick={toggleCallback}>{term.value}</a>
            ({term.count}) {term.selected ? '+' : ''}
        </li>)
    }

    toggleFacetTerm = (streamId, facet, term) => {
        const parameters = this.props.urlHandler.deriveParameters((urlState) => {
            const streamState = urlState.getStream(streamId)
            let filterTerms = this.getSelectedTerms(streamState, facet)

            if (filterTerms.includes(term.handle)) {
                filterTerms = filterTerms.splice(filterTerms.indexOf(term.handle), 1)
            } else {
                filterTerms.push(term.handle)
            }

            streamState.setFilter(
                facet.handle,
                _.isEmpty(filterTerms) ? null : { terms: filterTerms })
        })

        app.getRouter().push(this.props.route.route, parameters)
    }

    getSelectedTerms = (streamState, facet) => {
        return ((streamState.getParameters().facets || {})[facet.handle] || {}).terms || []
    }
}

ExampleProductListFilterTastic.propTypes = {
    data: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,

    // From connector
    route: PropTypes.object.isRequired,

    // Facet definition as provided by Frontastic
    facets: PropTypes.instanceOf(Entity).isRequired,

    // From urlHandlerConnector
    urlHandler: PropTypes.instanceOf(UrlHandler),
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
