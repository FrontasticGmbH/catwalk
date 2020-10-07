//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import logo from '../../../../layout/logo.svg'

import AtomsIcon from '../../atoms/icons/icon'
import MoleculesCartLink from '../../molecules/cart/cart-link'

class OrganismsHead extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<header className='c-navbar c-page-head' id='js-banner' style={{ position: 'relative' }}>
            <button className='c-navbar__button' aria-label='Toggle Menu' title='Toggle label' data-ft-sequential-nav-controls='js-ft-sequential-nav-menu'>
                <AtomsIcon icon='menu' iconSize='base' />
            </button>

            <div className='c-logo c-navbar__logo'>
                <a href='/'>
                    <img className='c-navbar__logo-image' src={logo} alt='Frontastic' />
                </a>
            </div>

            <button className='c-navbar__button'>
                <AtomsIcon icon='search' iconSize='base' />
            </button>

            <MoleculesCartLink />
        </header>)
    }
}

OrganismsHead.propTypes = {
}

OrganismsHead.defaultProps = {
}

export default ComponentInjector.return('OrganismsHead', OrganismsHead)
