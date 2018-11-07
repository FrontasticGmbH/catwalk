import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import AtomsButton from '../../../patterns/10-atoms/10-buttons/10-button'
import AtomsHeading from '../../../patterns/10-atoms/20-headings/10-heading'

import app from '../../../app/app'

class Login extends Component {
    constructor(props) {
        super(props)

        this.state = {
            login_email: 'kore@frontastic.cloud',
            login_password: 'password',
        }
    }

    render () {
        return (<div className='o-layout o-form'>
            <div className='o-layout__item o-form__item u-1/1'>
                <label htmlFor='login_email' className='c-form__label'>E-Mail</label>
                <input
                    id='login_email'
                    className='c-form__input'
                    type='text'
                    required
                    autoComplete='email'
                    value={this.state.login_email}
                    onChange={(event) => {
                        this.setState({ login_email: event.target.value })
                    }}
                />
            </div>
            <div className='o-layout__item o-form__item u-1/1'>
                <label htmlFor='login_password' className='c-form__label'>Password</label>
                <input
                    id='login_password'
                    className='c-form__input'
                    type='password'
                    required
                    autoComplete='password'
                    value={this.state.login_password}
                    onChange={(event) => {
                        this.setState({ login_password: event.target.value })
                    }}
                />
            </div>
            <div className='o-layout__item o-form__item u-1/1'>
                <AtomsButton
                    type='primary'
                    disabled={!this.state.login_email || !this.state.login_password}
                    onClick={() => {
                        app.getLoader('context').login(this.state.login_email, this.state.login_password)
                    }}>
                    Anmelden
                </AtomsButton>
            </div>
        </div>)
    }
}

Login.propTypes = {
}

Login.defaultProps = {
}

export default Login
