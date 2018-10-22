import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsPrice from '../80-prices/10-price'

class AtomsPriceList extends Component {
    render () {
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
