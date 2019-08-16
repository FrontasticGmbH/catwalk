/**
 * @param {object} nodeData
 * @return {array} Found errors
 */
const errorExtractor = (nodeData) => {
    return Object.keys(nodeData.stream).map((streamKey) => {
        if (typeof nodeData.stream[streamKey].ok !== 'undefined' && !nodeData.stream[streamKey].ok) {
            return {
                stream: {
                    [streamKey]: nodeData.stream[streamKey],
                }
            }
        }
        return false
    }).filter(element => (element !== false))
}

export default errorExtractor
