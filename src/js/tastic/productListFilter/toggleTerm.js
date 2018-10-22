import _ from 'lodash'

function toggleTerm (facet, term, selectFacetValue, removeFacetValue) {
    let selectedTerms = _.filter(facet.terms, { selected: true })

    if (_.includes(selectedTerms, term)) {
        _.pull(selectedTerms, term)
    } else {
        selectedTerms.push(term)
    }

    if (!selectedTerms.length) {
        removeFacetValue(facet)
    } else {
        selectFacetValue(
            facet,
            {
                terms: _.map(selectedTerms, (term) => {
                    return term.handle
                }),
            }
        )
    }
}

export default toggleTerm
