//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import { connect } from 'react-redux'

import AtomsButton from '../../../patterns/atoms/buttons/button'
import AtomsHeading from '../../../patterns/atoms/headings/heading'
import Grow from '../../../component/grow'
import ComponentInjector from '../../../app/injector'

import Login from './login'
import Reset from './reset'
import Register from './register'

class AccountLoginForm extends Component {
    constructor (props) {
        super(props)

        this.state = {
            form: 'login',
        }
    }

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (
            <div className='o-layout'>
                <div className='o-layout__item u-1/1'>
                    <AtomsHeading type='alpha'>Anmelden oder Registrieren</AtomsHeading>
                </div>
                <div className='o-layout__item u-1/1'>
                    <span className='c-button-row'>
                        <AtomsButton
                            type='primary'
                            full
                            outline={!(this.state.form === 'login')}
                            onClick={() => {
                                this.setState({ form: 'login' })
                            }}
                        >
                            Anmelden
                        </AtomsButton>
                        <AtomsButton
                            type='primary'
                            full
                            outline={!(this.state.form === 'register')}
                            onClick={() => {
                                this.setState({ form: 'register' })
                            }}
                        >
                            Registrieren
                        </AtomsButton>
                    </span>
                </div>
                <div
                    className='o-layout__item u-1/1 u-2/3@lap u-1/2@desk'
                    style={{ margin: '2em auto', display: 'block' }}
                >
                    <Grow in={this.state.form === 'login'}>
                        <Login />
                        <AtomsButton
                            full
                            onClick={() => {
                                this.setState({ form: 'reset' })
                            }}
                        >
                            Passwort vergessen
                        </AtomsButton>
                    </Grow>
                    <Grow in={this.state.form === 'register'}>
                        <Register />
                    </Grow>
                    <Grow in={this.state.form === 'reset'}>
                        <Reset />
                    </Grow>
                </div>
            </div>
        )
    }
}

AccountLoginForm.propTypes = {}

AccountLoginForm.defaultProps = {}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
    }
})(ComponentInjector.return('AccountLoginForm', AccountLoginForm))
