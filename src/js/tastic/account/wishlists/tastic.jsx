import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { FormattedMessage } from 'react-intl'
import _ from 'lodash'

import AtomsButton from '../../../patterns/10-atoms/10-buttons/10-button'
import AtomsHeading from '../../../patterns/10-atoms/20-headings/10-heading'
import Grow from '../../../component/grow'
import Notifications from '../../../component/notifications'

import app from '../../../app/app'

import AccountLoginForm from '../login/form'
import AccountBar from '../bar'
import Wishlist from './wishlist'

class AccountWishlistsTastic extends Component {
    render () {
        if (!this.props.context.session.loggedIn) {
            return <AccountLoginForm />
        }

        let wishlists = this.props.rawData.stream.__master
        return (<div className='o-layout'>
            <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                <AccountBar selected='wishlists' />
            </div>
            <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                <AtomsHeading type='alpha'>
                    <FormattedMessage id={'account.wishlists'} />
                </AtomsHeading>
                <Notifications />
                {wishlists.length === 1 ?
                    <Wishlist wishlist={wishlists[0]} /> :
                    <ul className='c-wishlists'>
                    {_.map(wishlists, (wishlist) => {
                        return (<li key={wishlist.wishlistId}>
                            {wishlist.name}
                        </li>)
                    })}
                    </ul>}
            </div>
        </div>)
    }
}

AccountWishlistsTastic.propTypes = {
    context: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

AccountWishlistsTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(AccountWishlistsTastic)
