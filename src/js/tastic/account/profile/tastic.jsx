//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { FormattedMessage } from 'react-intl'
import _ from 'lodash'

import AtomsButton from '../../../patterns/atoms/buttons/button'
import AtomsHeading from '../../../patterns/atoms/headings/heading'

import app from '../../../app/app'

import AccountLoginForm from '../login/form'
import AccountBar from '../bar'

class AccountProfileTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            profile_salutation: '',
            profile_firstName: '',
            profile_lastName: '',
            profile_email: '',
            profile_phone_prefix: '+49',
            profile_phone: '',
            profile_birthday_day: '',
            profile_birthday_month: '',
            profile_birthday_year: '',
            profile_password: '',
            profile_password_new: '',
            profile_password_repeat: '',
        }
    }

    static getDerivedStateFromProps (nextProps, nextState) {
        if (nextProps.context.session.account && !nextState.profile_email) {
            let account = nextProps.context.session.account || { data: {} }
            return {
                profile_salutation: account.salutation || '',
                profile_firstName: account.firstName || '',
                profile_lastName: account.lastName || '',
                profile_email: account.email || '',
                profile_phone_prefix: account.data.phone_prefix || '+49',
                profile_phone: account.data.phone || '',
                profile_birthday_day: new Date(account.birthday).getDate(),
                profile_birthday_month: new Date(account.birthday).getMonth() + 1,
                profile_birthday_year: new Date(account.birthday).getFullYear(),
            }
        }

        return nextState
    }

    hasAllRequiredAndIsChanged = () => {
        let account = this.props.context.session.account || { data: {} }

        return (
            !!(
                this.state.profile_salutation &&
                this.state.profile_firstName &&
                this.state.profile_lastName &&
                this.state.profile_birthday_day &&
                this.state.profile_birthday_month &&
                this.state.profile_birthday_year
            ) &&
            (this.state.profile_salutation !== account.salutation ||
                this.state.profile_firstName !== account.firstName ||
                this.state.profile_lastName !== account.lastName ||
                this.state.profile_birthday_day !== new Date(account.birthday).getDate() ||
                this.state.profile_birthday_month !== new Date(account.birthday).getMonth() + 1 ||
                this.state.profile_birthday_year !== new Date(account.birthday).getFullYear())
        )
    }

    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.props.context.session.loggedIn) {
            return <AccountLoginForm />
        }

        return (
            <div className='o-layout'>
                <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                    <AccountBar selected='profile' />
                </div>
                <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                    <AtomsHeading type='alpha'>
                        <FormattedMessage id={'account.profile'} />
                    </AtomsHeading>
                    <div className='o-layout'>
                        <div className='o-layout__item u-1/1 u-1/2@lap u-1/2@desk c-form'>
                            <div className='c-form__item'>
                                <label htmlFor='profile_salutation' className='c-form__label c-form__label--required'>
                                    Anrede
                                </label>
                                <select
                                    id='profile_salutation'
                                    className='c-form__select'
                                    required
                                    autoComplete='salutation'
                                    value={this.state.profile_salutation}
                                    onChange={(event) => {
                                        this.setState({ profile_salutation: event.target.value })
                                    }}
                                >
                                    <option value=''>Bitte Auswählen</option>
                                    <option value='Herr'>Herr</option>
                                    <option value='Frau'>Frau</option>
                                </select>
                            </div>
                            <div className='c-form__item'>
                                <label htmlFor='profile_firstName' className='c-form__label c-form__label--required'>
                                    Vorname
                                </label>
                                <input
                                    id='profile_firstName'
                                    className='c-form__input'
                                    type='text'
                                    required
                                    autoComplete='firstName'
                                    value={this.state.profile_firstName}
                                    onChange={(event) => {
                                        this.setState({ profile_firstName: event.target.value })
                                    }}
                                />
                            </div>
                            <div className='c-form__item'>
                                <label htmlFor='profile_lastName' className='c-form__label c-form__label--required'>
                                    Nachname
                                </label>
                                <input
                                    id='profile_lastName'
                                    className='c-form__input'
                                    type='text'
                                    required
                                    autoComplete='lastName'
                                    value={this.state.profile_lastName}
                                    onChange={(event) => {
                                        this.setState({ profile_lastName: event.target.value })
                                    }}
                                />
                            </div>
                            <div className='c-form__item'>
                                <label htmlFor='profile_email' className='c-form__label c-form__label--required'>
                                    E-Mail
                                </label>
                                <input
                                    id='profile_email'
                                    className='c-form__input'
                                    type='email'
                                    required
                                    disabled
                                    autoComplete='email'
                                    value={this.state.profile_email}
                                />
                            </div>
                            <div className='c-form__item'>
                                <label htmlFor='profile_phone' className='c-form__label'>
                                    Telefon
                                </label>
                                <div className='o-layout'>
                                    <div className='o-layout__item u-1/3'>
                                        <select
                                            id='profile_phone_prefix'
                                            className='c-form__select'
                                            autoComplete='salutation'
                                            value={this.state.profile_phone_prefix}
                                            onChange={(event) => {
                                                this.setState({ profile_phone_prefix: event.target.value })
                                            }}
                                        >
                                            <option value=''>Bitte Auswählen</option>
                                            <option value='+49'>+49</option>
                                        </select>
                                    </div>
                                    <div className='o-layout__item u-2/3'>
                                        <input
                                            id='profile_phone'
                                            className='c-form__input'
                                            type='number'
                                            required
                                            autoComplete='phone'
                                            value={this.state.profile_phone}
                                            onChange={(event) => {
                                                this.setState({ profile_phone: event.target.value })
                                            }}
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className='c-form__item'>
                                <label className='c-form__label c-form__label--required'>Geburtstag</label>
                                <div className='o-layout'>
                                    <div className='o-layout__item u-1/3'>
                                        <select
                                            required
                                            id='profile_birthday_day'
                                            className='c-form__select'
                                            value={this.state.profile_birthday_day}
                                            autoComplete='bday-day'
                                            onChange={(event) => {
                                                this.setState({ profile_birthday_day: event.target.value })
                                            }}
                                        >
                                            <option value=''>Bitte Auswählen</option>
                                            {_.map(_.range(1, 32), (day) => {
                                                return (
                                                    <option key={day} value={day}>
                                                        {day}
                                                    </option>
                                                )
                                            })}
                                        </select>
                                    </div>
                                    <div className='o-layout__item u-1/3'>
                                        <select
                                            required
                                            id='profile_birthday_month'
                                            className='c-form__select'
                                            value={this.state.profile_birthday_month}
                                            autoComplete='bday-month'
                                            onChange={(event) => {
                                                this.setState({ profile_birthday_month: event.target.value })
                                            }}
                                        >
                                            <option value=''>Bitte Auswählen</option>
                                            {_.map(_.range(1, 13), (month) => {
                                                return (
                                                    <option key={month} value={month}>
                                                        {month}
                                                    </option>
                                                )
                                            })}
                                        </select>
                                    </div>
                                    <div className='o-layout__item u-1/3'>
                                        <select
                                            required
                                            id='profile_birthday_year'
                                            className='c-form__select'
                                            value={this.state.profile_birthday_year}
                                            autoComplete='bday-year'
                                            onChange={(event) => {
                                                this.setState({ profile_birthday_year: event.target.value })
                                            }}
                                        >
                                            <option value=''>Bitte Auswählen</option>
                                            {_.map(_.range(2018, 1900), (year) => {
                                                return (
                                                    <option key={year} value={year}>
                                                        {year}
                                                    </option>
                                                )
                                            })}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div className='c-form__item'>
                                <AtomsButton
                                    type='primary'
                                    full
                                    disabled={!this.hasAllRequiredAndIsChanged()}
                                    onClick={() => {
                                        app.getLoader('context').updateUser({
                                            salutation: this.state.profile_salutation,
                                            firstName: this.state.profile_firstName,
                                            lastName: this.state.profile_lastName,
                                            phonePrefix: this.state.profile_phone_prefix,
                                            phone: this.state.profile_phone,
                                            birthdayDay: this.state.profile_birthday_day,
                                            birthdayMonth: this.state.profile_birthday_month,
                                            birthdayYear: this.state.profile_birthday_year,
                                        })
                                    }}
                                >
                                    Aktualisieren
                                </AtomsButton>
                            </div>
                        </div>
                        <div className='o-layout__item u-1/1 u-1/2@lap u-1/2@desk c-form'>
                            <div className='c-form__item'>
                                <label htmlFor='profile_password' className='c-form__label c-form__label--required'>
                                    Altes Password
                                </label>
                                <input
                                    id='profile_password'
                                    className='c-form__input'
                                    type='password'
                                    required
                                    pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}'
                                    value={this.state.profile_password}
                                    onChange={(event) => {
                                        this.setState({ profile_password: event.target.value })
                                    }}
                                />
                            </div>
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
                                <label
                                    htmlFor='profile_password_repeat'
                                    className='c-form__label c-form__label--required'
                                >
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
                                    disabled={
                                        !(
                                            this.state.profile_password &&
                                            this.state.profile_password_new &&
                                            this.state.profile_password_repeat &&
                                            this.state.profile_password_new === this.state.profile_password_repeat
                                        )
                                    }
                                    onClick={() => {
                                        app.getLoader('context').updatePassword(
                                            this.state.profile_password,
                                            this.state.profile_password_new
                                        )
                                        this.setState({
                                            profile_password: '',
                                            profile_password_new: '',
                                            profile_password_repeat: '',
                                        })
                                    }}
                                >
                                    Passwort ändern
                                </AtomsButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

AccountProfileTastic.propTypes = {
    context: PropTypes.object.isRequired,
}

AccountProfileTastic.defaultProps = {}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
    }
})(AccountProfileTastic)
