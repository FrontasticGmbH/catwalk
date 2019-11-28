import PropTypes from 'prop-types';
import ComponentInjector from './app/injector'

const AppContainer = ({children}) => children

AppContainer.propTypes = {
    children: PropTypes.node
}

export default ComponentInjector.return("AppContainer", AppContainer)
