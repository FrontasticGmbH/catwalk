import PropTypes from 'prop-types'
import ComponentInjector from './app/injector'

/**
 * This component is wrapped around most of the application,
 * and can be overwritten using the component injector.
 */
const AppContainer = ({ children }) => {
    return children
}

AppContainer.propTypes = {
    children: PropTypes.node,
}

export default ComponentInjector.return('AppContainer', AppContainer)
