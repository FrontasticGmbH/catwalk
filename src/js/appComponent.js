import React from 'react'
import { Router, Switch, Route } from 'react-router-dom'
import { Provider } from 'react-redux'
import IntlProvider from './app/intlProvider'
import PropTypes from 'prop-types';

import Node from './node'
import Preview from './preview'

const AppComponent = ({app}) => {
    return (
        <Provider store={app.getStore()}>
            <IntlProvider>
                <Router history={app.history}>
                    <Switch>
                        <Route
                            exact
                            path={app.getRouter().reactRoute('Frontastic.Frontend.Preview.view')}
                            component={Preview}
                        />

                        <Route component={Node} />
                    </Switch>
                </Router>
            </IntlProvider>
        </Provider>
    )
}

AppComponent.propTypes = {
    app: PropTypes.object,
}


export default AppComponent