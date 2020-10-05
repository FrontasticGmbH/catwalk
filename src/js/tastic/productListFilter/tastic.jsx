//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import _ from 'lodash'
import Entity from '../../app/entity'
import UrlHandler from '../../app/urlHandler'

import SvgIcon from '../../patterns/atoms/icons/icon'
import SelectionPane from './selectionPane'

import app from '../../app/app'
import facetConnector from '../../app/connector/facet'
import categoryConnector from '../../app/connector/category'
import urlHandlerConnector from '../../app/connector/urlHandler'

class ProductListFilterTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            showFilterSelection: false,
        }
    }

    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
            {this.renderCategorySelector(productList)}

            <button
                className='c-button  c-button--secondary  c-button--full u-margin-bottom'
                data-ft-sequential-nav-controls='js-ft-sequential-nav-filter'
                onClick={this.showFilterSelection}
            >
                <SvgIcon icon='sliders' />
                <span>Filter</span>
            </button>

            <SelectionPane
                onClose={this.closeFilterSelection}
                facets={productList.facets}
                show={this.state.showFilterSelection}
                selectFacetValue={this.selectFacetValue}
                removeFacetValue={this.removeFacetValue}
                facetValues={this.getFacetValues(this.props.tastic.configuration.stream)}
                facetConfiguration={this.props.facets.data}
            />

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

    closeFilterSelection = () => {
        this.setState({ showFilterSelection: false })
    }

    showFilterSelection = () => {
        this.setState({ showFilterSelection: true })
    }

    renderCategorySelector = (productList) => {
        /*
         * TODO: Is the category-list really part of the filter tastic or is it a dedicated one? What happens to
         * filters applied when selecting a different category?
         */
        const categoryFacet = this.getCategoryFacet(productList.facets, this.props.facets.data)

        if (!categoryFacet) {
            // eslint-disable-next-line no-console
            console.warn('No category facet found.')
            return null
        }

        return (<div className='c-filter-bar'>
            <fieldset className='c-filter-bar__scrollable'>
                {_.map(this.filterCategoryTerms(categoryFacet.terms), (term) => {
                    return (
                        <label key={term.handle} className='c-filter-bar__item' htmlFor={'filter-' + term.handle}>
                            <input
                                className='u-hidden-visually'
                                name='filter-checkboxes'
                                type='checkbox'
                                id={'filter-' + term.handle}
                                value={term.selected}
                                onChange={(e) => {
                                    const newTerms = _.clone(this.getCategoryFacetValue(categoryFacet).terms)
                                    if (newTerms.includes(term.handle)) {
                                        _.pull(newTerms, term.handle)
                                    } else {
                                        newTerms.push(term.handle)
                                    }
                                    if (_.isEmpty(newTerms)) {
                                        this.removeFacetValue(categoryFacet)
                                    } else {
                                        this.selectFacetValue(categoryFacet, { terms: newTerms })
                                    }
                                }}
                            />
                            <span className='c-filter-bar__button'>{term.name}</span>
                        </label>
                    )
                })}
            </fieldset>
        </div>)
    }

    getCategoryFacetValue = (categoryFacet) => {
        let currentValues = this.getFacetValues(this.props.tastic.configuration.stream)
        return currentValues[categoryFacet.handle] || { terms: [] }
    }

    filterCategoryTerms = (terms) => {
        return _.uniqBy(terms, 'name')
    }

    getCategoryFacet = (facets, facetDefinitions, count = 10) => {
        let categoryFacetDefinition = null

        for (let i = 0; i < facetDefinitions.length; i++) {
            const facetDefinition = facetDefinitions[i]

            if (facetDefinition.attributeType === 'categoryId') {
                categoryFacetDefinition = facetDefinition
                break
            }
        }

        if (!categoryFacetDefinition) {
            return null
        }

        const categoryFacet = _.find(facets, { handle: categoryFacetDefinition.attributeId })

        _.remove(categoryFacet.terms, (term) => {
            return term.value === '_'
        })

        // @TODO: Extract to facet settings
        categoryFacet.terms = _.take(_.reverse(_.sortBy(categoryFacet.terms, 'count')), count)

        // @HACK: Should be part of the term already
        categoryFacet.terms = _.map(categoryFacet.terms, (term) => {
            const category = _.find(this.props.categories.data, { categoryId: term.handle })

            // Resilience
            if (!category) {
                return term
            }

            term.name = category.name
            return term
        })

        return categoryFacet
    }
}

ProductListFilterTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    route: PropTypes.object.isRequired,
    urlHandler: PropTypes.instanceOf(UrlHandler),
    // Facet definition as provided by Frontastic
    facets: PropTypes.instanceOf(Entity).isRequired,
    categories: PropTypes.instanceOf(Entity).isRequired,
}

ProductListFilterTastic.defaultProps = {
}

export default compose(
    connect(facetConnector),
    connect(categoryConnector),
    connect(urlHandlerConnector),
    connect((globalState) => {
        let streamParameters = globalState.app.route.parameters.s || {}

        return {
            route: globalState.app.route,
            streamParameters: streamParameters,
        }
    }),
)(ProductListFilterTastic)
