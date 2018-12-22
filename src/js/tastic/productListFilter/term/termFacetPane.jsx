import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

class TermFacetPane extends Component {
    render () {
        const facet = this.props.facet

        return (<ul className='c-tableview'>
            {_.map(
                this.getTerms(),
                (term) => {
                    return (<li key={term.handle} className='c-tableview__cell'>
                        <button
                            className={'c-button c-tableview__button' + (this.getValue().terms.includes(term.handle) ? ' is-active' : '')}
                            onClick={() => {
                                const newTerms = _.clone(this.getValue().terms)
                                if (newTerms.includes(term.handle)) {
                                    _.pull(newTerms, term.handle)
                                } else {
                                    newTerms.push(term.handle)
                                }
                                if (_.isEmpty(newTerms)) {
                                    this.props.removeFacetValue(facet)
                                } else {
                                    this.props.selectFacetValue(facet, { terms: newTerms })
                                }
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

    getValue = () => {
        let currentValue = this.props.facetValue || { terms: [] }
        // FIXME: Workaround for HTTP query parsing bug
        if (!_.isArray(currentValue.terms)) {
            currentValue.terms = _.values(currentValue.terms)
        }
        return currentValue
    }

    sortTerms = (terms) => {
        const options = this.props.facetConfig.facetOptions

        if (!options.has('sortOrder')) {
            return terms
        }

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

        if (!options.has('stripLabelPrefix')) {
            return terms
        }

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
    // FIXME: Workaround for HTTP query parsing bug
    facetValue: PropTypes.oneOfType([PropTypes.array, PropTypes.object]),
    selectFacetValue: PropTypes.func.isRequired,
    removeFacetValue: PropTypes.func.isRequired,
}

TermFacetPane.defaultProps = {
    facetValue: [],
}

export default TermFacetPane
