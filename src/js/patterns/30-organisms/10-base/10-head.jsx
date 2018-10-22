import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import logo from '../../../../layout/logo.svg'

import AtomsIcon from '../../10-atoms/40-icons/10-icon'
import MoleculesCartLink from '../../20-molecules/50-cart/10-cart-link'

class OrganismsHead extends Component {
    render () {
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
