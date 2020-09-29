//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Price from '../../../patterns/atoms/prices/price'

class PriceFacetTitle extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (
            <Fragment>
                <span>{this.props.name || this.props.facet.key}</span>
                {this.props.facet.selected
                    ? <span>
                        <span> (</span>
                        <Price value={parseInt(this.props.facet.value.min, 10)} currency='EUR' />
                        <span> - </span>
                        <Price value={parseInt(this.props.facet.value.max, 10)} currency='EUR' />
                        <span>)</span>
                    </span>
                    : null
                }
            </Fragment>
        )
    }
}

PriceFacetTitle.propTypes = {
    facet: PropTypes.object.isRequired,
    name: PropTypes.node,
}

PriceFacetTitle.defaultProps = {}

export default PriceFacetTitle
