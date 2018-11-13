import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

class Address extends Component {
    render () {
        return (<address className='c-address'>
            {this.props.address.firstName} {this.props.address.lastName}<br />
            {this.props.address.streetName} {this.props.address.streetNumber}<br />
            {this.props.address.postalCode} {this.props.address.city} ({this.props.address.country})<br />
        </address>)
    }
}

Address.propTypes = {
    address: PropTypes.object.isRequired,
}

Address.defaultProps = {
}

export default Address
