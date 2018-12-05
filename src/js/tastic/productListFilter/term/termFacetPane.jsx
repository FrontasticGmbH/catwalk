import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import toggleTerm from '../toggleTerm'

class TermFacetPane extends Component {
    render () {
        const facet = this.props.facet

        // TODO: Handle term selection state through URL provided facetValue
        return (<ul className='c-tableview'>
            {_.map(
                this.getTerms(),
                (term) => {
                    return (<li key={term.handle} className='c-tableview__cell'>
                        <button
                            className={'c-button c-tableview__button' + (term.selected ? ' is-active' : '')}
                            onClick={() => {
                                toggleTerm(facet, term, this.props.selectFacetValue, this.props.removeFacetValue)
                            }}>
                            {term.name}
                        </button>
                    </li>)
                }
            )}
        </ul>)
    }

    getTerms = () => {
        let terms = _.cloneDeep(this.props.facet.terms)
        terms = this.sortTerms(terms)
        return this.removeLabelPrefix(terms)
    }

    sortTerms = (terms) => {
        const options = this.props.facetConfig.facetOptions

        const sortOrder = options.get('sortOrder')
        const stripPrefix = options.get('stripLabelPrefix')

        if (sortOrder === 'sort-undefined') {
            return terms
        }

        const preprocessLabel = (stripPrefix ?
            (label) => {
                return _.toInteger(label.replace(/^([\d]+)-.*$/, '$1'))
            }
            :
            _.identity
        )

        terms = _.sortBy(terms, (term) => {
            return preprocessLabel(term.name)
        })

        if (sortOrder === 'sort-descending') {
            terms = _.reverse(terms)
        }

        return terms
    }

    removeLabelPrefix = (terms) => {
        const options = this.props.facetConfig.facetOptions

        const stripPrefix = options.get('stripLabelPrefix')

        if (!stripPrefix) {
            return terms
        }

        return _.map(terms, (term) => {
            term.name = term.name.replace(/^[\d]+-(.*)$/, '$1')
            return term
        })
    }
}

TermFacetPane.propTypes = {
    facet: PropTypes.object.isRequired,
    facetConfig: PropTypes.object.isRequired,
    // facetValue: PropTypes.object,
    selectFacetValue: PropTypes.func.isRequired,
    removeFacetValue: PropTypes.func.isRequired,
}

TermFacetPane.defaultProps = {}

export default TermFacetPane
