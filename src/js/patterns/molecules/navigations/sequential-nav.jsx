//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'
import AtomsIcon from '../../atoms/icons/icon'

class MoleculesSequentialNav extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<div className='c-overlay' data-ft-sequential-nav-overlay id='js-ft-sequential-nav-menu'>
            <nav className='c-sequential-nav' data-ft-sequential-nav>
                <div className='c-sequential-nav__panel c-sequential-nav__panel--level-1' id='ft-nav-menu-level-1'
                    data-ft-sequential-nav-id='ft-nav-menu-level-1'>
                    <div className='c-sequential-nav__header'>
                        <AtomsButton className='c-sequential-nav__primary-action' data-ft-sequential-nav-dismiss>
                            <AtomsIcon icon='cross' iconSize='base' />
                        </AtomsButton>
                    </div>
                    <div className='c-sequential-nav__content'>
                        <ul className='c-tableview'>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' aria-controls='' data-ft-sequential-nav-link=''>New</button>
                            </li>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' aria-controls='ft-nav-menu-level-1-2'
                                    data-ft-sequential-nav-link='ft-nav-menu-level-1-2'>Women
                                </button>
                            </li>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' data-ft-sequential-nav-link=''>Men</button>
                            </li>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' data-ft-sequential-nav-link=''>Accessoires</button>
                            </li>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' data-ft-sequential-nav-link=''>Brands</button>
                            </li>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link c-tableview__link--primary' data-ft-sequential-nav-link=''>Sale</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className='c-sequential-nav__panel c-sequential-nav__panel--level-2' id='ft-nav-menu-level-1-2'
                    data-ft-sequential-nav-id='ft-nav-menu-level-1-2'>
                    <div className='c-sequential-nav__header'>
                        <AtomsButton className='c-sequential-nav__secondary-action' data-ft-sequential-nav-link='ft-nav-menu-level-1'>
                            <AtomsIcon icon='arrow-left' iconSize='base' />
                        </AtomsButton>
                        <h3 className='c-sequential-nav__title'>Woman</h3>
                        <AtomsButton className='c-sequential-nav__primary-action' data-ft-sequential-nav-dismiss>
                            <AtomsIcon icon='cross' iconSize='base' />
                        </AtomsButton>
                    </div>
                    <div className='c-sequential-nav__content'>
                        <ul className='c-tableview'>
                            <li className='c-tableview__cell'>
                                <a className='c-tableview__link is-active' href='#'>Women</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Men</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Accessoires</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Brands</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Sale</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>)
    }
}

MoleculesSequentialNav.propTypes = {
}

MoleculesSequentialNav.defaultProps = {
}

export default ComponentInjector.return('MoleculesSequentialNav', MoleculesSequentialNav)
