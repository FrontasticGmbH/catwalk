import React from 'react'
import { connect } from 'react-redux'

/**
 * An object of possible selectors for tastify()
 * 
 * The key of the selector is being used as the prop name and also as the `configuration.connect`.
 * 
 * This means, if `configuration.connect.context` is set, the function below with the key `context` is being executed,
 * and the result will be passed in the `context` prop.
 */
const availableSelectors = {
    context: (state) => {
        return state.app.context
    },
    deviceType: (state) => {
        return state.renderContext.deviceType
    },
    isServerSideRendering: (state) => {
        return state.renderContext.serverSideRendering
    },
}

/**
 * Connects the tastic to the redux store, if necessary
 * 
 * It uses the above availableSelectors object and checks which of those selectors are enabled in the configuration.
 * If no selector is active, the component will not be connected to redux at all.
 */
const connectedTasticForConfiguration = (Tastic, configuration) => {
    const selectors = {}

    Object.keys(availableSelectors).forEach(selectorName => {
        if (configuration.connect[selectorName]) {
            selectors[selectorName] = availableSelectors[selectorName]
        }
    })


    if (Object.keys(selectors).length === 0 ) {
        // Apparently, no selector should be used, thus we do not need to connect the tastic to redux at all.
        return Tastic
    }

    return connect(
        (state) => {
            const props = {}

            Object.keys(selectors).forEach(selectorName => {
                if (configuration.connect[selectorName]) {
                    props[selectorName] = selectors[selectorName](state)
                }
            })

            return props
        }
    )(Tastic)
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
 * @param {boolean} configuration.connect.deviceType - Whether to pass the deviceType
 * @param {boolean} configuration.connect.isServerSideRendering - Whether we should pass a flag `isServerSideRendering`
 */
const tastify = (configuration = {}) => {
    return (WrappedComponent) => {
        if (!configuration.connect) {
            configuration.connect = {}
        }

        class Tastic extends React.Component {
            render () {
                const props = filterPropsForConfiguration(configuration, this.props)

                return <WrappedComponent {...props} />
            }
        };

        Tastic.displayName = `Tastic(${getDisplayName(WrappedComponent)})`

        return connectedTasticForConfiguration(Tastic, configuration)
    }
}

const getDisplayName = (WrappedComponent) => {
    return WrappedComponent.displayName || WrappedComponent.name || 'UnknownTastic'
}

export default tastify
