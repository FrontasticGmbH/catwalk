import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Link } from 'react-router-dom'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import AtomsPrice from '../../atoms/prices/price'

import NoImage from '../../../../layout/noImage.svg'
import RemoteImage from '../../../remoteImage'

class MoleculesStreamTeaser extends Component {
    render () {
        let product = this.props.product
        let variant = this.props.variant || product.variants[0]

        return (<article className='c-teaser o-block' itemScope itemType='http://schema.org/Product'>


            <img
                loading='lazy'
                width={100}
                height={200}
                alt={this.props.alt}
                src={variant.images[0] || NoImage}
            />

            <div className='o-block__body'>
                    <div className='c-teaser__body'>
                        <div className='c-teaser__caption'>
                            {product.name}
                        </div>
                    </div>
                </div>
        </article>)
    }
}

MoleculesStreamTeaser.propTypes = {
    product: PropTypes.object.isRequired,
    variant: PropTypes.object,
    showStrikePrice: PropTypes.bool,
    showPercent: PropTypes.bool,
}

MoleculesStreamTeaser.defaultProps = {
    showStrikePrice: false,
    showPercent: false,
    variant: null,
}

// These are just default props for the pattern library
MoleculesStreamTeaser.testProps = {
    product: fixture.product,
    showStrikePrice: true,
    showPercent: true,
}

export default ComponentInjector.return('MoleculesStreamTeaser', MoleculesStreamTeaser)
