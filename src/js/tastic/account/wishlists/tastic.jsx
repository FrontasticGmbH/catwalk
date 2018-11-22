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
    constructor (props) {
        super(props)

        this.state = {
            name: '',
        }
    }

    render () {
        let wishlists = this.props.rawData.stream.__master
        let selectedWishlist = null

        if (this.props.selected) {
            selectedWishlist = _.find(wishlists, { wishlistId: this.props.selected }) || null
        } else if (wishlists.length === 1) {
            selectedWishlist = wishlists[0]
        }

        return (<div className='o-layout'>
            <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                <AccountBar selected='wishlists' />
            </div>
            <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                <AtomsHeading type='alpha'>
                    <FormattedMessage id={'account.wishlists'} />
                </AtomsHeading>
                <Notifications />
                {selectedWishlist ?
                    <Fragment>
                        <AtomsButton
                            onClick={() => {
                                app.getRouter().push('Frontastic.Frontend.Master.Account.wishlists', { wishlist: null })
                            }}
                        >
                            Back
                        </AtomsButton>
                        <Wishlist wishlist={selectedWishlist} />
                    </Fragment> :
                    <ul className='c-wishlists'>
                        {_.map(wishlists, (wishlist) => {
                        return (<li key={wishlist.wishlistId}>
                            <button
                                className='c-wishlists__item'
                                onClick={() => {
                                    app.getRouter().push('Frontastic.Frontend.Master.Account.wishlists', { wishlist: wishlist.wishlistId })
                                }}
                            >
                                <AtomsHeading type='gamma'>{wishlist.name}</AtomsHeading>
                            </button>
                        </li>)
                    })}
                    </ul>}
                <AtomsHeading type='beta'>
                    <FormattedMessage id={'account.new_wishlist'} />
                </AtomsHeading>
                {this.props.context.session.loggedIn ? (<form className='c-form o-layout'>
                    <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                        <label htmlFor='wishlist_name' className='c-form__label'>Wunschzettel</label>
                        <input
                            id='wishlist_name'
                            className='c-form__input'
                            type='text'
                            required
                            value={this.state.name}
                            onChange={(event) => {
                                this.setState({ name: event.target.value })
                            }}
                        />
                    </div>
                    <div className='c-form__item o-layout__item u-1/1 u-1/2@lap' style={{ verticalAlign: 'bottom' }}>
                        <AtomsButton
                            type='primary' full
                            htmlType='submit'
                            disabled={!this.state.name}
                            onClick={(event) => {
                                event.preventDefault()
                                event.stopPropagation()

                                app.getLoader('wishlist').create(this.state.name)
                            }}>
                            Anlegen
                        </AtomsButton>
                    </div>
                </form>) : null}
            </div>
        </div>)
    }
}

AccountWishlistsTastic.propTypes = {
    context: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    selected: PropTypes.string,
}

AccountWishlistsTastic.defaultProps = {
    selected: null,
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
            selected: globalState.app.route.get('wishlist', null),
        }
    }
)(AccountWishlistsTastic)
