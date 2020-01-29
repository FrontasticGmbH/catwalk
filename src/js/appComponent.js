import React from 'react'
import { Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'
import IntlProvider from './app/intlProvider'
import PropTypes from 'prop-types'

import Node from './node'
import Preview from './preview'
import AppContainer from './appContainer'
import ScrollbarContainer from './scrollbarContainer'

const AppComponent = ({ app, renderRouter }) => {
    const appLayout = (
        <AppContainer>
            <ScrollbarContainer>
                <Switch>
                    <Route
                        exact
                        path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                        component={Preview}
                    />

                    <Route component={Node}/>
                </Switch>
            </ScrollbarContainer>
        </AppContainer>
    )

    return (
        <Provider store={app.getStore()}>
            <IntlProvider>
                {renderRouter(appLayout)}
            </IntlProvider>
        </Provider>
    )
}

AppComponent.propTypes = {
    app: PropTypes.object,
    renderRouter: PropTypes.func,
}

export default AppComponent
