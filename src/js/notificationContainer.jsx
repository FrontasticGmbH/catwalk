import PropTypes from 'prop-types'
import ComponentInjector from './app/injector'

/**
 * This component is wrapped around half of the application,
 * and can be overwritten using the component injector.
 *
 * This can be used to send own notifications.
 */
const NotificationContainer = ({ children }) => {
    return children
}

NotificationContainer.propTypes = {
    children: PropTypes.node,
}

export default ComponentInjector.return('NotificationContainer', NotificationContainer)
