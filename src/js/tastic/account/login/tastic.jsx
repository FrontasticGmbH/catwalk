//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import AtomsButton from '../../../patterns/atoms/buttons/button'
import AtomsHeading from '../../../patterns/atoms/headings/heading'

import app from '../../../app/app'

import AccountLoginForm from './form'
import AccountBar from '../bar'

class AccountLoginTastic extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (this.props.context.session.loggedIn) {
            return (
                <div className='o-layout'>
                    <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                        <AccountBar />
                    </div>
                    <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                        <AtomsHeading type='alpha'>
                            Hallo {this.props.context.session.account.firstName}{' '}
                            {this.props.context.session.account.lastName}
                        </AtomsHeading>
                        <div className='u-right'>
                            <AtomsButton
                                outline
                                onClick={() => {
                                    app.getLoader('context').logout()
                                }}
                            >
                                Abmelden
                            </AtomsButton>
                        </div>
                    </div>
                </div>
            )
        }

        return <AccountLoginForm />
    }
}

AccountLoginTastic.propTypes = {
    context: PropTypes.object.isRequired,
}

AccountLoginTastic.defaultProps = {}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
    }
})(AccountLoginTastic)
