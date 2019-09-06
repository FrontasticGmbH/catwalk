import Entity from '../entity'
import emptyEntity from '../../helper/emptyEntity'

const connector = (globalState) => {
    return {
        facets: globalState.facet.facets || emptyEntity,
    }
}

export default connector
