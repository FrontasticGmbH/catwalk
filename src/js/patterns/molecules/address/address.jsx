import React, { Component } from 'react'
import PropTypes from 'prop-types'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'

class MoleculesAddress extends Component {
    render () {
        return (<div className='c-address'>
            <address className='c-address__address'>
                {this.props.address.firstName} {this.props.address.lastName}{this.props.short ? ', ' : <br />}
                {this.props.address.streetName} {this.props.address.streetNumber}{this.props.short ? ', ' : <br />}
                {this.props.address.postalCode} {this.props.address.city} ({this.props.address.country})
            </address>
            {this.props.editAddress || this.props.removeAddress ? (<div className='c-address__buttons'>
                {this.props.editAddress ? <AtomsButton onClick={() => { this.props.editAddress(this.props.address) }}>Edit</AtomsButton> : null}
                {this.props.removeAddress ? <AtomsButton onClick={() => { this.props.removeAddress(this.props.address) }}>Remove</AtomsButton> : null}
            </div>) : null}
            {this.props.setDefaultBillingAddress || this.props.setDefaultShippingAddress ? (<div className='c-address__options'>
                {this.props.setDefaultShippingAddress ? (<div className='c-form__item o-layout__item u-1/1'>
                    <input
                        className='c-form__input'
                        type='checkbox'
                        id={'address-default-shipping-address-' + this.props.address.addressId}
                        checked={this.props.address.isDefaultShippingAddress}
                        onChange={() => { this.props.setDefaultShippingAddress(this.props.address) }}
                    />
                    <label className='c-form__label' htmlFor={'address-default-shipping-address-' + this.props.address.addressId}>
                        Standard-Lieferadresse
                    </label>
                </div>) : null}
                {this.props.setDefaultBillingAddress ? (<div className='c-form__item o-layout__item u-1/1'>
                    <input
                        className='c-form__input'
                        type='checkbox'
                        id={'address-default-billing-address-' + this.props.address.addressId}
                        checked={this.props.address.isDefaultBillingAddress}
                        onChange={() => { this.props.setDefaultBillingAddress(this.props.address) }}
                    />
                    <label className='c-form__label' htmlFor={'address-default-billing-address-' + this.props.address.addressId}>
                        Standard-Rechnungsadresse
                    </label>
                </div>) : null}
            </div>) : null}
        </div>)
    }
}

MoleculesAddress.propTypes = {
    address: PropTypes.object.isRequired,
    short: PropTypes.bool,
    setDefaultBillingAddress: PropTypes.func,
    setDefaultShippingAddress: PropTypes.func,
    editAddress: PropTypes.func,
    removeAddress: PropTypes.func,
}

MoleculesAddress.defaultProps = {
    short: false,
}

// These are just default props for the pattern library
MoleculesAddress.testProps = {
    address: {
        addressId: 'test',
        firstName: 'Erika',
        lastName: 'Mustermann',
        streetName: 'Musterweg',
        streetNumber: '42',
        additionalStreetInfo: '1. Etage',
        postalCode: '12345',
        city: 'Musterstadt',
        country: 'DE',
    },
    setDefaultBillingAddress: () => {},
    setDefaultShippingAddress: () => {},
    editAddress: () => {},
    removeAddress: () => {},
}

export default ComponentInjector.return('MoleculesAddress', MoleculesAddress)
