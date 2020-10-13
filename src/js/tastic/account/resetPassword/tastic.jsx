//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import AtomsButton from '../../../patterns/atoms/buttons/button'
import AtomsHeading from '../../../patterns/atoms/headings/heading'

import app from '../../../app/app'

class AccountResetPasswordTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            profile_password_new: '',
            profile_password_repeat: '',
        }
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (
            <div className='o-layout'>
                <div className='o-layout__item u-1/1'>
                    <AtomsHeading type='alpha'>Reset Password</AtomsHeading>
                    <form className='c-form'>
                        <div className='c-form__item'>
                            <label htmlFor='profile_password_new' className='c-form__label c-form__label--required'>
                                Neues Password
                            </label>
                            <input
                                placeholder='Mindestens 8 Buchstaben/Zahlen und 1 Sonderzeichen'
                                id='profile_password_new'
                                className='c-form__input'
                                type='password'
                                required
                                pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}'
                                value={this.state.profile_password_new}
                                onChange={(event) => {
                                    this.setState({ profile_password_new: event.target.value })
                                }}
                            />
                        </div>
                        <div className='c-form__item'>
                            <label htmlFor='profile_password_repeat' className='c-form__label c-form__label--required'>
                                Neues Password (Wiederholung)
                            </label>
                            <input
                                placeholder='Wiederholung des neuen Passworts'
                                id='profile_password_repeat'
                                className='c-form__input'
                                type='password'
                                required
                                pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}'
                                value={this.state.profile_password_repeat}
                                onChange={(event) => {
                                    this.setState({ profile_password_repeat: event.target.value })
                                }}
                            />
                        </div>
                        <div className='c-form__item'>
                            <AtomsButton
                                type='primary'
                                full
                                htmlType='submit'
                                disabled={
                                    !(
                                        this.state.profile_password_new &&
                                        this.state.profile_password_repeat &&
                                        this.state.profile_password_new === this.state.profile_password_repeat
                                    )
                                }
                                onClick={(event) => {
                                    event.preventDefault()
                                    event.stopPropagation()

                                    app.getLoader('context').resetPassword(
                                        this.props.confirmationToken,
                                        this.state.profile_password_new
                                    )
                                    this.setState({
                                        profile_password_new: '',
                                        profile_password_repeat: '',
                                    })
                                }}
                            >
                                Passwort ändern
                            </AtomsButton>
                        </div>
                    </form>
                </div>
            </div>
        )
    }
}

AccountResetPasswordTastic.propTypes = {
    confirmationToken: PropTypes.string,
}

AccountResetPasswordTastic.defaultProps = {}

export default connect((globalState, props) => {
    return {
        ...props,
        confirmationToken: globalState.app.route.get('confirmationToken', null),
    }
})(AccountResetPasswordTastic)
