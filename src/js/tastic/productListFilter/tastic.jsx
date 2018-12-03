import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import _ from 'lodash'
import Entity from '../../app/entity'

import SvgIcon from '../../patterns/10-atoms/40-icons/10-icon'
import SelectionPane from './selectionPane'

import toggleTerm from './toggleTerm'

import app from '../../app/app'
import facetConnector from '../../app/connector/facet'
import categoryConnector from '../../app/connector/category'

class ProductListFilterTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            showFilterSelection: false,
        }
    }

    render () {
        let productList = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!productList || !productList.facets || productList.facets.length === 0) {
            console.warn('Product list does not contain facets. Won\'t display filter tastic.')
            return null
        }

        if (!this.props.facets.isComplete()) {
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
                valuesFromTastic={!!this.props.route.historyState && this.props.route.historyState.comeFromTastic}
                facetConfiguration={this.props.facets.data}
            />

        </Fragment>)
    }

    componentDidMount = () => {
        // Indicate that we have processed the current URL
        this.replaceUrl(this.getCurrentStreamParameters())
    }

    getFacetValues = (streamId) => {
        if (this.props.streamParameters[streamId]) {
            return this.props.streamParameters[streamId].facets || {}
        }
        return {}
    }

    selectFacetValue = (facet, value) => {
        let streamParameters = this.getCurrentStreamParameters()

        _.set(
            streamParameters,
            [this.props.tastic.configuration.stream, 'facets', facet.handle],
            _.extend(
                {
                    type: facet.type,
                    handle: facet.handle,
                },
                value
            )
        )

        this.updateUrl(streamParameters)
    }

    removeFacetValue = (facet) => {
        let streamParameters = this.getCurrentStreamParameters()

        _.unset(
            streamParameters,
            [this.props.tastic.configuration.stream, 'facets', facet.handle],
        )

        this.updateUrl(streamParameters)
    }

    updateUrl = (streamParameters) => {
        const newParameters = this.deriveRouteParameters({
            s: streamParameters,
        })

        app.getRouter().push(this.props.route.route, newParameters, { comeFromTastic: true })
    }

    replaceUrl = (streamParameters) => {
        const newParameters = this.deriveRouteParameters({
            s: streamParameters,
        })

        app.getRouter().replace(this.props.route.route, newParameters, { comeFromTastic: true })
    }

    deriveRouteParameters = (updatedParameters) => {
        return _.extend(
            {},
            // nodeId comes from route itself already
            _.omit(this.props.route.parameters, ['nodeId', 'environment', 'project', 'locale']),
            updatedParameters
        )
    }

    getCurrentStreamParameters = () => {
        return _.cloneDeep(this.props.route.parameters.s || {})
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
                                    toggleTerm(categoryFacet, term, this.selectFacetValue, this.removeFacetValue)
                                }}
                            />
                            <span className='c-filter-bar__button'>{term.name}</span>
                        </label>
                    )
                })}
            </fieldset>
        </div>)
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
    streamParameters: PropTypes.object.isRequired,
    // Facet definition as provided by Frontastic
    facets: PropTypes.instanceOf(Entity).isRequired,
    categories: PropTypes.instanceOf(Entity).isRequired,
}

ProductListFilterTastic.defaultProps = {
}

export default compose(
    connect(facetConnector),
    connect(categoryConnector),
    connect((globalState) => {
        let streamParameters = globalState.app.route.parameters.s || {}

        return {
            route: globalState.app.route,
            streamParameters: streamParameters,
        }
    }),
)(ProductListFilterTastic)
