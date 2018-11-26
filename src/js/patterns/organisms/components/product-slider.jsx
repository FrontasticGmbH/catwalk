import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'
import Slider from '../../../../js/component/slider'

import fixture from '../../fixture'

import MoleculesProductTeaser from '../../molecules/teasers/product-teaser'

class OrganismsProductSlider extends Component {
    render () {
        return (<Slider>
            <div className='c-slider__item    js-slider__item    u-3/4    u-2/5@lap    u-2/7@desk'>
                <MoleculesProductTeaser product={fixture.product} />
            </div>
            <div className='c-slider__item    js-slider__item    u-3/4    u-2/5@lap    u-2/7@desk'>
                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
            </div>
            <div className='c-slider__item    js-slider__item    u-3/4    u-2/5@lap    u-2/7@desk'>
                <MoleculesProductTeaser product={fixture.product} showPercent />
            </div>
            <div className='c-slider__item    js-slider__item    u-3/4    u-2/5@lap    u-2/7@desk'>
                <MoleculesProductTeaser product={fixture.product} showStrikePrice showPercent />
            </div>
            <div className='c-slider__item    js-slider__item    u-3/4    u-2/5@lap    u-2/7@desk'>
                <MoleculesProductTeaser product={fixture.product} />
            </div>
            <div className='c-slider__item    js-slider__item    u-3/4    u-2/5@lap    u-2/7@desk'>
                <MoleculesProductTeaser product={fixture.product} />
            </div>
        </Slider>)
    }
}

OrganismsProductSlider.propTypes = {
}

OrganismsProductSlider.defaultProps = {
}

export default ComponentInjector.return('OrganismsProductSlider', OrganismsProductSlider)
