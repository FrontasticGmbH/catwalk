import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

class OrganismsFooter extends Component {
    render () {
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
