//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { FormattedMessage } from 'react-intl'
import _ from 'lodash'

import AtomsButton from '../../../patterns/atoms/buttons/button'
import AtomsHeading from '../../../patterns/atoms/headings/heading'
import AtomsNotification from '../../../patterns/atoms/notifications/notification'
import MoleculesAddress from '../../../patterns/molecules/address/address'

import app from '../../../app/app'

import AccountLoginForm from '../login/form'
import AccountBar from '../bar'

import AddressEdit from './edit'

class AccountAddressesTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            edit: false,
            address: null,
        }
    }

    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.props.context.session.loggedIn) {
            return <AccountLoginForm />
        }

        let addresses = this.props.rawData.stream.__master
        return (
            <div className='o-layout'>
                <div className='o-layout__item u-1/1 u-1/3@lap u-1/4@desk'>
                    <AccountBar selected='addresses' />
                </div>
                <div className='o-layout__item u-1/1 u-2/3@lap u-3/4@desk'>
                    <AtomsHeading type='alpha'>
                        <FormattedMessage id={'account.addresses'} />
                    </AtomsHeading>
                    {!addresses.length ? (
                        <AtomsNotification message='No addresses yet' type='info' />
                    ) : (
                        <div className='o-layout'>
                            {_.map(addresses, (address) => {
                                return (
                                    <div className='o-layout__item u-1/1 u-1/2@lap u-1/3@desk' key={address.addressId}>
                                        <MoleculesAddress
                                            address={address}
                                            setDefaultBillingAddress={(address) => {
                                                app.getLoader('context').setDefaultBillingAddress(address)
                                            }}
                                            setDefaultShippingAddress={(address) => {
                                                app.getLoader('context').setDefaultShippingAddress(address)
                                            }}
                                            editAddress={(address) => {
                                                this.setState({
                                                    edit: true,
                                                    address: address,
                                                })
                                            }}
                                            removeAddress={(address) => {
                                                app.getLoader('context').removeAddress(address)
                                            }}
                                        />
                                    </div>
                                )
                            })}
                        </div>
                    )}
                    {this.state.edit ? (
                        <Fragment>
                            <AddressEdit
                                address={this.state.address}
                                onChange={(address) => {
                                    this.setState({ address: address })
                                }}
                            />
                            <AtomsButton
                                onClick={() => {
                                    this.setState({ edit: false, address: null })
                                }}
                            >
                                Abbrechen
                            </AtomsButton>
                            <AtomsButton
                                type='primary'
                                onClick={() => {
                                    if (this.state.address.addressId) {
                                        app.getLoader('context').updateAddress(this.state.address)
                                    } else {
                                        app.getLoader('context').addAddress(this.state.address)
                                    }

                                    this.setState({ edit: false, address: null })
                                }}
                            >
                                Speichern
                            </AtomsButton>
                        </Fragment>
                    ) : (
                        <AtomsButton
                            type='primary'
                            onClick={() => {
                                this.setState({ edit: true })
                            }}
                        >
                            Neue Adresse anlegen
                        </AtomsButton>
                    )}
                </div>
            </div>
        )
    }
}

AccountAddressesTastic.propTypes = {
    context: PropTypes.object.isRequired,
    rawData: PropTypes.object,
}

AccountAddressesTastic.defaultProps = {}

export default connect((globalState, props) => {
    return {
        ...props,
        context: globalState.app.context,
    }
})(AccountAddressesTastic)
