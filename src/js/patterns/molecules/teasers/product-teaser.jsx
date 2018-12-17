import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Link } from 'react-router-dom'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import AtomsPrice from '../../atoms/prices/price'

import NoImage from '../../../../layout/noImage.svg'
import RemoteImage from '../../../remoteImage'

class MoleculesProductTeaser extends Component {
    render () {
        let product = this.props.product
        let variant = this.props.variant || product.variants[0]

        return (<article className='c-teaser o-block' itemScope itemType='http://schema.org/Product'>
            {/* FIXME: Temporarily set slug as url */}
            <Link itemProp='url' className='c-teaser__full-link'
                  to={this.props.product._url}>
                <figure className='c-teaser__figure o-block__figure'>
                    <RemoteImage
                        className='c-teaser__image'
                        url={variant.images[0] || NoImage}
                        alt={product.name}
                        cropRatio='1:1'
                        itemProp='image'
                        options={{ crop: 'pad', background: 'white' }}
                    />
                </figure>
                <div className='o-block__body'>
                    <div className='c-teaser__body'>
                        <div className='c-teaser__caption'>
                            <h3 className='c-teaser__title c-heading-teaser' itemProp='name'>
                                {product.name}
                            </h3>
                            {variant.attributes.designer ?
                                <div className='c-heading-teaser-subheading'>{variant.attributes.designer}</div>
                            : null}
                        </div>
                        <div className='c-teaser__prices' itemScope itemType='http://schema.org/Offer'>
                            <AtomsPrice className='c-teaser__price'
                                value={variant.discountedPrice || variant.price}
                                highlight={variant.discountedPrice && (this.props.showPercent || this.props.showStrikePrice)} />
                            {variant.discountedPrice && this.props.showStrikePrice ? <span>
                                &nbsp;<AtomsPrice className='c-teaser__price' old value={variant.price} />
                            </span> : null}
                            {variant.discountedPrice && this.props.showPercent ? <span className='c-highlight'>
                                &nbsp;-&thinsp;{100 - Math.ceil(variant.discountedPrice / variant.price * 100)}&thinsp;%
                            </span> : null}
                        </div>
                    </div>
                </div>
            </Link>
        </article>)
    }
}

MoleculesProductTeaser.propTypes = {
    product: PropTypes.object.isRequired,
    variant: PropTypes.object,
    showStrikePrice: PropTypes.bool,
    showPercent: PropTypes.bool,
}

MoleculesProductTeaser.defaultProps = {
    showStrikePrice: false,
    showPercent: false,
    variant: null,
}

// These are just default props for the pattern library
MoleculesProductTeaser.testProps = {
    product: fixture.product,
    showStrikePrice: true,
    showPercent: true,
}

export default ComponentInjector.return('MoleculesProductTeaser', MoleculesProductTeaser)
