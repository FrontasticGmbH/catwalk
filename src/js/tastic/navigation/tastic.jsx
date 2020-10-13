//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Link from '../../app/link'

import logo from '../../../layout/logo.svg'

import LanguageSelector from './languageSelector'
import MiniCart from './miniCart'
import Sequential from './sequential'
import SvgIcon from '../../patterns/atoms/icons/icon'

class NavigationTastic extends Component {
    constructor (props) {
        super(props)

        this.state = {
            showNavigation: false,
        }
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        let customLogo = this.props.tastic.schema.get('logo') || null

        return (<Fragment>
            <div className='c-navbar'>
                {this.props.data.tree && this.props.data.tree.children && this.props.data.tree.children.length ?
                    <button
                        className='c-button'
                        disabled={!this.props.data.tree}
                        onClick={() => {
                            this.setState({ showNavigation: true })
                        }}
                    >
                        <SvgIcon icon='menu' />
                    </button> : null}

                <Link path='/' className='c-logo c-navbar__logo'>
                    {customLogo && customLogo.media ?
                        <img className='c-navbar__logo-image' src={customLogo.media.file} alt='Frontastic' /> :
                        <img className='c-navbar__logo-image' src={logo} alt='Frontastic' />}
                </Link>

                {this.props.tastic.schema.get('showSearch') ?
                    <button className='c-button c-navbar__button' disabled>
                        <SvgIcon icon='search' />
                    </button> : null}

                {this.props.tastic.schema.get('showLanguageSelector') ? <LanguageSelector /> : null}

                {this.props.tastic.schema.get('showCart') ? <MiniCart /> : null}

                {this.props.data.tree && this.props.data.tree.children && this.props.data.tree.children.length ?
                    <div className={'c-overlay' + (this.state.showNavigation ? ' is-visible' : '')}>
                        <nav className='c-sequential-nav' data-ft-sequential-nav=''>
                            <Sequential
                                root={this.props.data.tree}
                                onClose={() => {
                                    this.setState({ showNavigation: false })
                                }} />
                        </nav>
                    </div> : null}
            </div>
            <div className='c-navbar__spacing' />
        </Fragment>)
    }
}

NavigationTastic.propTypes = {
    data: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

NavigationTastic.defaultProps = {
}

export default NavigationTastic
