import Entity from '../entity'
import emptyEntity from '../../helper/emptyEntity'

const connector = (globalState) => {
    return {
        categories: globalState.category.categories || emptyEntity,
    }
}

export default connector
