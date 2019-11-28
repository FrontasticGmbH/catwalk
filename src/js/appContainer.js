import React from 'react'
import PropTypes from 'prop-types'
import ComponentInjector from './app/injector'
import ScrollbarComponent from './scrollbarComponent'

const AppContainer = ({children}) => <ScrollbarComponent>{children}</ScrollbarComponent>

AppContainer.propTypes = {
    children: PropTypes.node
}

export default ComponentInjector.return("AppContainer", AppContainer)
