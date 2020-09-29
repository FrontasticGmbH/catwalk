//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsPrice from '../prices/price'

class AtomsPriceList extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<ul className='c-price-list'>
            <li className='c-price-list__item'>
                <AtomsPrice value={2342} />
            </li>
            <li className='c-price-list__item'>
                <AtomsPrice highlight value={2342} />
            </li>
        </ul>)
    }
}

AtomsPriceList.propTypes = {
}

AtomsPriceList.defaultProps = {
}

export default ComponentInjector.return('AtomsPriceList', AtomsPriceList)
