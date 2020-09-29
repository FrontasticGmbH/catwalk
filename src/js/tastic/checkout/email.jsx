//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'
import { generateId } from 'frontastic-common'

class Email extends Component {
    constructor (props) {
        super(props)

        this.state = {
            email: '',
            password: '',
            createAccount: true,
            registerForNewsletter: false,
        }
    }

    debouncedUpdate = _.debounce(() => {
        this.props.onChange(this.state)
    }, 200)

    updateState = (stateChange) => {
        this.setState(stateChange)
        this.debouncedUpdate()
    }

    doesUserExist = (email) => {
        // @TODO: Check with backend if user already exists
        return this.state.email.includes('@frontastic.cloud')
    }

    checkboxId = generateId()

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<div className='c-form o-layout'>
            <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                <label htmlFor='checkout_mail' className='c-form__label'>E-Mail</label>
                <input
                    id='checkout_mail'
                    className='c-form__input'
                    type='email'
                    value={this.state.email}
                    required
                    autoComplete='email'
                    onChange={(event) => {
                        this.updateState({ email: event.target.value })
                    }}
                />
            </div>
            {this.doesUserExist(this.state.email) ? <Fragment>
                <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                    <label htmlFor='checkout_password' className='c-form__label'>Passwort</label>
                    <input
                        id='checkout_password'
                        className='c-form__input'
                        type='password'
                        required
                        value={this.state.password}
                        onChange={(event) => {
                            this.updateState({ password: event.target.value })
                        }}
                    />
                </div>
                <div className='c-form__item o-layout__item u-1/1' align='right'>
                    <button
                        className='c-button c-button--primary'
                        onClick={() => {
                            // eslint-disable-next-line no-console
                            console.log('Login…', this.state)
                        }}
                    >
                        Anmelden
                    </button>
                </div>
            </Fragment> : <Fragment>
                <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                    <input
                        className='c-form__input'
                        type='checkbox'
                        id={this.checkboxId + '_account'}
                        checked={this.state.createAccount}
                        onChange={(event) => {
                            this.updateState({ createAccount: event.target.checked })
                        }}
                    />
                    <label className='c-form__label' htmlFor={this.checkboxId + '_account'}>
                        Create Account
                    </label>
                </div>
            </Fragment>}
            {this.doesUserExist(this.state.email) ? null :
            <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                <input
                    className='c-form__input'
                    type='checkbox'
                    checked={this.state.registerForNewsletter}
                    id={this.checkboxId + '_newsletter'}
                    onChange={(event) => {
                        this.updateState({ registerForNewsletter: event.target.checked })
                    }}
                />
                <label className='c-form__label' htmlFor={this.checkboxId + '_newsletter'}>
                    Register for newsletter
                </label>
            </div>}
        </div>)
    }
}

Email.propTypes = {
    onChange: PropTypes.func.isRequired,
}

Email.defaultProps = {}

export default Email
