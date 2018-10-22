import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import Price from '../../../patterns/10-atoms/80-prices/10-price'
import productConnector from '../connector'

class ProductPriceTastic extends Component {
    render () {
        if (!this.props.product || !this.props.variant) {
            return null
        }

        let showPercent = this.props.tastic.schema.get('showPercent')
        let showStrikePrice = this.props.tastic.schema.get('showStrikePrice')
        let isReduced = !!this.props.variant.discountedPrice

        let optionPrice = (this.props.option && this.props.option.price) || 0

        return (<div className='c-page-section'>
            <ul className='c-price-list'>
                {optionPrice ? <Fragment>
                    <li className='c-price-list__item'>
                        <span className='c-price'>
                            <Price value={isReduced ? this.props.variant.discountedPrice : this.props.variant.price} />
                        </span>
                    </li>
                    <li className='c-price-list__item'>
                        <span className='c-price'>
                            + <Price value={optionPrice} />
                        </span>
                    </li>
                </Fragment> : null}
                <li className='c-price-list__item'>
                    <Price
                        value={(isReduced ? this.props.variant.discountedPrice : this.props.variant.price) + optionPrice}
                        highlight={isReduced && (showPercent || showStrikePrice)}
                        className='u-text-l' />
                </li>
                {isReduced ?
                    <Fragment>
                        {showStrikePrice ?
                            <li className='c-price-list__item'>
                                <Price value={this.props.variant.price + optionPrice} old />
                            </li> : null}
                        {showPercent ?
                            <li className='c-price-list__item'>
                                <span className='c-highlight'>
                                    Save {100 - Math.ceil(this.props.variant.discountedPrice / this.props.variant.price * 100)}&thinsp;%
                                </span>
                            </li> : null}
                    </Fragment>
                : null}
            </ul>
        </div>)
    }
}

ProductPriceTastic.propTypes = {
    tastic: PropTypes.object.isRequired,
    product: PropTypes.object,
    variant: PropTypes.object,
    option: PropTypes.object,
}

ProductPriceTastic.defaultProps = {
    product: null,
    variant: null,
}

export default connect(productConnector)(ProductPriceTastic)
