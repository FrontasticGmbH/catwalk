//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'
import AtomsIcon from '../../atoms/icons/icon'

class MoleculesSequentialFilterNav extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<div className='c-overlay' data-ft-sequential-nav-overlay id='js-ft-sequential-nav-filter'>
            <nav className='c-sequential-nav' data-ft-sequential-nav>
                <div className='c-sequential-nav__panel c-sequential-nav__panel--level-1' id='ft-nav-filter-level-1'
                    data-ft-sequential-nav-id='ft-nav-filter-level-1'>
                    <div className='c-sequential-nav__header c-sequential-nav__header--dark'>
                        <h3 className='c-sequential-nav__title'>Filter</h3>
                        <button className='c-sequential-nav__primary-action' data-ft-sequential-nav-dismiss>
                            <AtomsIcon icon='cross' iconSize='base' />
                        </button>
                    </div>
                    <div className='c-sequential-nav__content'>
                        <ul className='c-tableview'>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' aria-controls='' data-ft-sequential-nav-link=''>Marke</button>
                            </li>
                            <li className='c-tableview__cell'>
                                <button className='c-tableview__link' aria-controls='ft-nav-filter-level-1-2'
                                    data-ft-sequential-nav-link='ft-nav-filter-level-1-2'>Farbe
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className='c-sequential-nav__panel c-sequential-nav__panel--level-2' id='ft-nav-filter-level-1-2'
                    data-ft-sequential-nav-id='ft-nav-filter-level-1-2'>
                    <div className='c-sequential-nav__header'>
                        <button className='c-sequential-nav__secondary-action' data-ft-sequential-nav-link='ft-nav-filter-level-1'>
                            <AtomsIcon icon='arrow-left' iconSize='base' />
                        </button>
                        <h3 className='c-sequential-nav__title'>Farbe</h3>
                        <button className='c-sequential-nav__primary-action' data-ft-sequential-nav-dismiss>
                            <AtomsIcon icon='cross' iconSize='base' />
                        </button>
                    </div>
                    <div className='c-sequential-nav__content'>
                        <ul className='c-tableview'>
                            <li className='c-tableview__cell'>
                                <a className='c-tableview__link' href='#'>Blau</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Grün</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Rot</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Grau</a>
                            </li>
                            <li className='c-tableview__cell c-tableview__cell--nested'>
                                <a className='c-tableview__link' href='#'>Weiß</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div className='c-overlay__bottom-bar'>
                <AtomsButton type='primary' full>10 Ergebnisse anzeigen</AtomsButton>
            </div>
        </div>)
    }
}

MoleculesSequentialFilterNav.propTypes = {
}

MoleculesSequentialFilterNav.defaultProps = {
}

export default ComponentInjector.return('MoleculesSequentialFilterNav', MoleculesSequentialFilterNav)
