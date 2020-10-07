//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'

import AtomsButton from '../../../patterns/atoms/buttons/button'

import app from '../../../app/app'

class Reset extends Component {
    constructor (props) {
        super(props)

        this.state = {
            reset_email: '',
        }
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<form className='c-form'>
            <div className='c-form__item'>
                <label htmlFor='reset_email' className='c-form__label'>E-Mail</label>
                <input
                    id='reset_email'
                    className='c-form__input'
                    type='email'
                    required
                    autoComplete='email'
                    value={this.state.reset_email}
                    onChange={(event) => {
                        this.setState({ reset_email: event.target.value })
                    }}
                />
            </div>
            <div className='c-form__item'>
                <AtomsButton
                    type='primary' full
                    htmlType='submit'
                    disabled={!this.state.reset_email}
                    onClick={(event) => {
                        event.preventDefault()
                        event.stopPropagation()

                        app.getLoader('context').requestPasswordReset(this.state.reset_email)
                    }}>
                    Passwort anfordern
                </AtomsButton>
            </div>
        </form>)
    }
}

Reset.propTypes = {
}

Reset.defaultProps = {
}

export default Reset
