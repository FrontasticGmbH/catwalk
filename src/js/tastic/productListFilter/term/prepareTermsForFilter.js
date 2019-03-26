import _ from "lodash";

export const prepareTermsForFilter = (terms, facetConfig) => {
    terms = _.cloneDeep(terms)
    terms = sortTerms(terms, facetConfig)
    return removeLabelPrefix(terms, facetConfig)
}

const sortTerms = (terms, facetConfig) => {
    const options = facetConfig.facetOptions

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
                return _.toInteger(label.replace(/^([\d]+)_.*$/, '$1'))
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

const removeLabelPrefix = (terms, facetConfig) => {
    const options = facetConfig.facetOptions

    if (!options.has('stripLabelPrefix')) {
        return terms
    }

    const stripPrefix = options.get('stripLabelPrefix')

    if (!stripPrefix) {
        return terms
    }

    return _.map(terms, (term) => {
        term.name = term.name.replace(/^[\d]+_(.*)$/, '$1')
        return term
    })
}
