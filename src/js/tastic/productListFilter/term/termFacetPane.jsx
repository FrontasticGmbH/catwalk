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
                facet.terms,
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
}

TermFacetPane.propTypes = {
    facet: PropTypes.object.isRequired,
    // facetValue: PropTypes.object,
    selectFacetValue: PropTypes.func.isRequired,
    removeFacetValue: PropTypes.func.isRequired,
}

TermFacetPane.defaultProps = {}

export default TermFacetPane
