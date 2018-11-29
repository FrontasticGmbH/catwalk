import Entity from '../entity'

const connector = (globalState) => {
    return {
        facets: globalState.facet.facets || new Entity(),
    }
}

export default connector
