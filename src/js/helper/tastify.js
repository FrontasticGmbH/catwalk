import React from 'react'
import { compose } from 'redux'
import { connect } from 'react-redux'

/**
 * Passes additional information from redux to the tastic.
 * This is just a helper to remove the dependency to redux from *some* tastics.
 */
const createConnectorChainForConfiguration = (configuration) => {
    const connectorChain = []

    if (configuration.connect.context) {
        connectorChain.push((state) => {
            return {
                context: state.app.context,
            }
        })
    }

    return connectorChain
}

/**
 * Removes some props from the tastic, for performance optimizations.
 */
const filterPropsForConfiguration = (configuration, originalProps) => {
    const props = {
        ...originalProps,
    }

    if (!configuration.connect.rawData) {
        delete props.rawData
    }

    if (!configuration.connect.node) {
        delete props.node
    }
    if (!configuration.connect.page) {
        delete props.page
    }
    if (!configuration.connect.tastic) {
        delete props.tastic
    }

    return props
}

/**
 * HOC for tastics. Makes sure only required stuff is passed, which can be used as a performance improvement.
 *
 * Defaults to only passing the data prop.
 * In combination with React.PureComponent or React.memo this might reduce re-renders.
 *
 * @experimental This is a work-in-progress and might change in the future.
 * @param {Object} configuration.connect
 * @param {boolean} configuration.connect.rawData - Whether to pass "rawData". Should not be needed anymore.
 * @param {boolean} configuration.connect.node - Whether to pass information about the current node.
 * @param {boolean} configuration.connect.page - Whether to pass information about the current page.
 * @param {boolean} configuration.connect.tastic - Whether to pass the schema of the current tastic. Rarely needed.
 * @param {boolean} configuration.connect.context - Whether to pass the frontastic context object.
 */
const tastify = (configuration = {}) => {
    return (WrappedComponent) => {
        if (!configuration.connect) {
            configuration.connect = {}
        }

        const connectorChain = createConnectorChainForConfiguration(configuration)
        class Tastic extends React.Component {
            render () {
                const props = filterPropsForConfiguration(configuration, this.props)

                return <WrappedComponent {...props} />
            }
        };

        Tastic.displayName = `Tastic(${getDisplayName(WrappedComponent)})`

        // This tastic needs nothing from redux => not connecting it at all.
        if (connectorChain.length === 0) {
            return Tastic
        }
        return connect(
            compose(...connectorChain)
        )(Tastic)
    }
}

const getDisplayName = (WrappedComponent) => {
    return WrappedComponent.displayName || WrappedComponent.name || 'UnknownTastic'
}

export default tastify
