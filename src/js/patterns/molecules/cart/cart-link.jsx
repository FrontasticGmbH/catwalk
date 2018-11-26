import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'
import AtomsIcon from '../../atoms/icons/icon'

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
