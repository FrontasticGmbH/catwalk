//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import { FormattedMessage } from 'react-intl'
import _ from 'lodash'

import app from '../../app/app'

class AccountBar extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.props.context.session.loggedIn) {
            return null
        }

        return (<nav className='c-page-navbar'>
            <ul className='c-page-navbar__scrollable'>
                {_.map(this.props.actions, (action) => {
                    return (<li key={action}
                        className={classnames({
                            'c-page-navbar__item': true,
                            'c-page-navbar__item--selected': (action === this.props.selected),
                        }, 'c-page-navbar__' + action)}
                    >
                        <button
                            className='c-page-navbar__button'
                            onClick={() => { app.getRouter().push('Frontastic.Frontend.Master.Account.' + action) }}
                        >
                            <FormattedMessage id={'account.' + action} />
                        </button>
                    </li>)
                })}
                <li className='c-page-navbar__item'>
                    <button
                        className='c-page-navbar__button'
                        onClick={() => {
                            app.getLoader('context').logout()
                        }}
                    >
                        <FormattedMessage id='account.logout' />
                    </button>
                </li>
            </ul>
        </nav>)
    }
}

AccountBar.propTypes = {
    context: PropTypes.object.isRequired,
    actions: PropTypes.array,
    selected: PropTypes.string,
}

AccountBar.defaultProps = {
    actions: ['profile', 'addresses', 'orders', 'wishlists', 'vouchers'],
    selected: null,
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(AccountBar)
