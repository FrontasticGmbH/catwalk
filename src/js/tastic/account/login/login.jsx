import React, { Component } from 'react'

import AtomsButton from '../../../patterns/atoms/buttons/button'

import app from '../../../app/app'

class Login extends Component {
    constructor (props) {
        super(props)

        this.state = {
            login_email: '',
            login_password: '',
        }
    }

    render () {
        return (<form className='c-form'>
            <div className='c-form__item'>
                <label htmlFor='login_email' className='c-form__label'>E-Mail</label>
                <input
                    id='login_email'
                    className='c-form__input'
                    type='email'
                    required
                    autoComplete='email'
                    value={this.state.login_email}
                    onChange={(event) => {
                        this.setState({ login_email: event.target.value })
                    }}
                />
            </div>
            <div className='c-form__item'>
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
            <div className='c-form__item'>
                <AtomsButton
                    type='primary' full
                    htmlType='submit'
                    disabled={!this.state.login_email || !this.state.login_password}
                    onClick={(event) => {
                        event.preventDefault()
                        event.stopPropagation()

                        app.getLoader('context').login(this.state.login_email, this.state.login_password)
                    }}>
                    Anmelden
                </AtomsButton>
            </div>
        </form>)
    }
}

Login.propTypes = {
}

Login.defaultProps = {
}

export default Login
