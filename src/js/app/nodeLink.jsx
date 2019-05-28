import React from 'react'

import PropTypes from 'prop-types'
import SymfonyLink from './link'

/**
 * Supports linking to a frontastic node
 *
 * @example
 * <NodeLink node={imprintNode}>Imprint</NodeLink>
 */
const NodeLink = ({ node, children, ...otherProps }) => {
    return (
        <SymfonyLink {...otherProps} route={'node_' + node.nodeId}>
            {children}
        </SymfonyLink>
    )
}

NodeLink.propTypes = {
    node: PropTypes.shape({
        nodeId: PropTypes.string.isRequired,
    }).isRequired,
    children: PropTypes.node,
    params: PropTypes.object,
}

export default NodeLink
