import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Price from '../../../patterns/10-atoms/80-prices/10-price'

class PriceFacetTitle extends Component {
    render () {
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
    name: PropTypes.string,
}

PriceFacetTitle.defaultProps = {}

export default PriceFacetTitle
