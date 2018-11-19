import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import AtomsButton from '../../../patterns/10-atoms/10-buttons/10-button'

class Address extends Component {
    render () {
        return (<div className='c-address'>
            <address className='c-address__address'>
                {this.props.address.firstName} {this.props.address.lastName}<br />
                {this.props.address.streetName} {this.props.address.streetNumber}<br />
                {this.props.address.postalCode} {this.props.address.city} ({this.props.address.country})<br />
            </address>
            <div className='c-address__buttons'>
                <AtomsButton onClick={() => { this.props.editAddress(this.props.address)} }>Edit</AtomsButton>
                <AtomsButton onClick={() => { this.props.removeAddress(this.props.address)} }>Remove</AtomsButton>
            </div>
            <div className='c-address__options'>
                <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                    <input
                        className='c-form__input'
                        type='checkbox'
                        id={'address-default-shipping-address-' + this.props.address.addressId}
                        checked={this.props.address.isDefaultShippingAddress}
                        onChange={() => { this.props.setDefaultShippingAddress(this.props.address)} }
                    />
                    <label className='c-form__label' htmlFor={'address-default-shipping-address-' + this.props.address.addressId}>
                        Standard-Lieferadresse
                    </label>
                </div>
                <div className='c-form__item o-layout__item u-1/1 u-1/2@lap'>
                    <input
                        className='c-form__input'
                        type='checkbox'
                        id={'address-default-billing-address-' + this.props.address.addressId}
                        checked={this.props.address.isDefaultBillingAddress}
                        onChange={() => { this.props.setDefaultBillingAddress(this.props.address)} }
                    />
                    <label className='c-form__label' htmlFor={'address-default-billing-address-' + this.props.address.addressId}>
                        Standard-Rechnungsadresse
                    </label>
                </div>
            </div>
        </div>)
    }
}

Address.propTypes = {
    address: PropTypes.object.isRequired,
    setDefaultBillingAddress: PropTypes.func.isRequired,
    setDefaultShippingAddress: PropTypes.func.isRequired,
    editAddress: PropTypes.func.isRequired,
    removeAddress: PropTypes.func.isRequired,
}

Address.defaultProps = {
}

export default Address
