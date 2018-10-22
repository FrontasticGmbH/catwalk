import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../10-atoms/10-buttons/10-button'
import AtomsIcon from '../../10-atoms/40-icons/10-icon'

class MoleculesCartLink extends Component {
    render () {
        return (<AtomsButton component='a' href='#' className='c-navbar__button c-cart-link' aria-label='Mein Warenkorb'>
            <AtomsIcon icon='bag' iconSize='base' />
            <span className='c-cart-link__badge'>2</span>
        </AtomsButton>)
    }
}

MoleculesCartLink.propTypes = {
}

MoleculesCartLink.defaultProps = {
}

export default ComponentInjector.return('MoleculesCartLink', MoleculesCartLink)
