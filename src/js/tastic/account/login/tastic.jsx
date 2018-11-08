import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import AtomsButton from '../../../patterns/10-atoms/10-buttons/10-button'
import AtomsHeading from '../../../patterns/10-atoms/20-headings/10-heading'
import Grow from '../../../component/grow'

import Login from './login'
import Register from './register'

class AccountLoginTastic extends Component {
    constructor(props) {
        super(props)

        this.state = {
            form: 'register',
        }
    }

    render () {
        if (this.props.context.session.loggedIn) {
            return null
        }

        return (<div className='o-layout'>
            <AtomsHeading type='alpha'>Anmelden oder Registrieren</AtomsHeading>
            <div className='o-layout__item u-1/1'>
                <span className='c-button-row'>
                    <AtomsButton
                        type='primary' full
                        outline={!(this.state.form === 'login')}
                        onClick={() => {
                            this.setState({ form: 'login' })
                        }}
                    >
                        Anmelden
                    </AtomsButton>
                    <AtomsButton
                        type='primary' full
                        outline={!(this.state.form === 'register')}
                        onClick={() => {
                            this.setState({ form: 'register' })
                        }}
                    >
                        Registrieren
                    </AtomsButton>
                </span>
            </div>
            <div className='o-layout__item u-1/1 u-2/3@lap u-1/2@desk' style={{ margin: '2em auto', display: 'block' }}>
                <Grow in={(this.state.form === 'login')}>
                    <Login />
                </Grow>
                <Grow in={(this.state.form === 'register')}>
                    <Register />
                </Grow>
            </div>
        </div>)
    }
}

AccountLoginTastic.propTypes = {
    context: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

AccountLoginTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(AccountLoginTastic)
