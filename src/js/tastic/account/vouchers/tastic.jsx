import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { FormattedMessage } from 'react-intl'

import AtomsHeading from '../../../patterns/atoms/headings/heading'

import AccountLoginForm from '../login/form'
import AccountBar from '../bar'

class AccountVouchersTastic extends Component {
    render () {
        if (!this.props.context.session.loggedIn) {
            return <AccountLoginForm />
        }

        return (<div className='o-layout'>
            <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                <AccountBar selected='vouchers' />
            </div>
            <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                <AtomsHeading type='alpha'>
                    <FormattedMessage id={'account.vouchers'} />
                </AtomsHeading>
                <p>
                    @TODO: Content
                </p>
            </div>
        </div>)
    }
}

AccountVouchersTastic.propTypes = {
    context: PropTypes.object.isRequired,
}

AccountVouchersTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(AccountVouchersTastic)
