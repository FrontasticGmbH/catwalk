import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import AtomsHeading from '../../../patterns/10-atoms/20-headings/10-heading'

import Login from './login'

class AccountLoginTastic extends Component {
    constructor(props) {
        super(props)

        this.state = {
            login_email: '',
            login_password: '',
        }
    }

    render () {
        if (this.props.context.session.loggedIn) {
            return
        }

        return (<div className='o-layout'>
            <AtomsHeading type='alpha'>Anmelden oder Registrieren</AtomsHeading>
            <div className='o-layout__item u-1/1 u-1/2@lap u-1/2@desk'>
                <AtomsHeading type='beta'>Anmelden</AtomsHeading>
                <Login />
            </div>
            <div className='c-cart__items o-layout__item u-1/1 u-1/2@lap u-1/2@desk'>
                <AtomsHeading type='beta'>Registrieren</AtomsHeading>
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
