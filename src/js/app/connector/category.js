import Entity from '../entity'

const connector = (globalState) => {
    return {
        categories: globalState.category.categories || new Entity(),
    }
}

export default connector
