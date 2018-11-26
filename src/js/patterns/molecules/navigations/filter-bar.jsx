import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'
import AtomsIcon from '../../atoms/icons/icon'

class MoleculesFilterBar extends Component {
    render () {
        return (<Fragment>
            <div className='c-filter-bar'>
                <div className='c-filter-bar__scrollable'>
                    <a className='c-filter-bar__button' href='#'>Jacken</a>
                    <a className='c-filter-bar__button' href='#'>Mäntel</a>
                    <a className='c-filter-bar__button' href='#'>Pullover</a>
                    <a className='c-filter-bar__button is-active' href='#'>Schlüpfer</a>
                    <a className='c-filter-bar__button' href='#'>Socken</a>
                    <a className='c-filter-bar__button' href='#'>Hosen</a>
                    <a className='c-filter-bar__button' href='#'>Accessoires</a>
                    <a className='c-filter-bar__button' href='#'>Socken</a>
                </div>
            </div>

            <AtomsButton type='secondary' full data-ft-sequential-nav-controls='js-ft-sequential-nav-filter'>
                <AtomsIcon icon='sliders' className='c-button__icon' />
                <span>Filter</span>
            </AtomsButton>
        </Fragment>)
    }
}

MoleculesFilterBar.propTypes = {
}

MoleculesFilterBar.defaultProps = {
}

export default ComponentInjector.return('MoleculesFilterBar', MoleculesFilterBar)
