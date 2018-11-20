import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import AtomsButton from '../../../patterns/10-atoms/10-buttons/10-button'
import AtomsHeading from '../../../patterns/10-atoms/20-headings/10-heading'
import MoleculesLineItem from '../../../patterns/20-molecules/50-cart/30-line-item'

class Wishlist extends Component {
    render () {
        return (<div className='c-wishlist o-layout'>
            <div className='o-layout__item u-1/1'>
                <AtomsHeading type='beta'>{this.props.wishlist.name}</AtomsHeading>
            </div>
            {_.map(this.props.wishlist.lineItems, (lineItem) => {
                return <MoleculesLineItem key={lineItem.lineItemId} lineItem={lineItem} />
            })}
        </div>)
    }
}

Wishlist.propTypes = {
    wishlist: PropTypes.object.isRequired,
}

Wishlist.defaultProps = {
}

export default Wishlist
