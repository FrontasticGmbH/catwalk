import Entity from '../app/entity'

/**
 * Extracts the current page from redux
 * 
 * If the current page has not been loaded yet, we default to an empty page.
 * That empty page will contain the head 
 */
const pageSelector = globalState => {
    let page = null
    if (globalState.node.pages[globalState.node.currentNodeId] &&
            globalState.node.nodeData[globalState.node.currentCacheKey]) {
        page = globalState.node.pages[globalState.node.currentNodeId]
    } else if (globalState.node.last.page) {
        page = new Entity({
            ...globalState.node.last.page.data,
            pageId: 'partial',
            regions: {
                head: globalState.node.last.page.data.regions.head,
                main: { regionId: 'main' },
                footer: { regionId: 'footer' },
            },
        })
    }
    return page
}

export default pageSelector
