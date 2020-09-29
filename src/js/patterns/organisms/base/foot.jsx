//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

class OrganismsFooter extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<footer className='c-page-foot'>
            <div className='o-wrapper'>
                <nav>
                    <ul className='c-nav-foot'>
                        <li className='c-nav-foot__item'>
                            <a className='c-nav-foot__link' href='#'>Impressum</a>
                        </li>
                        <li className='c-nav-foot__item'>
                            <a className='c-nav-foot__link' href='#'>Datenschutz</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </footer>)
    }
}

OrganismsFooter.propTypes = {
}

OrganismsFooter.defaultProps = {
}

export default ComponentInjector.return('OrganismsFooter', OrganismsFooter)
