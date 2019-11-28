import React from 'react'
import PropTypes from 'prop-types'
import ComponentInjector from './app/injector'
import ScrollbarComponent from './scrollbarComponent'

/**
 * This component is wrapped around most of the application, 
 * and can be overwritten using the component injector.
 * 
 * If you overwrite this, make sure to include the Frontastic Scrollbar Component,
 * unless you do not want it :-)
 */
const AppContainer = ({children}) => {
    return (
        <ScrollbarComponent>{children}</ScrollbarComponent>
    )
}

AppContainer.propTypes = {
    children: PropTypes.node
}

export default ComponentInjector.return("AppContainer", AppContainer)
