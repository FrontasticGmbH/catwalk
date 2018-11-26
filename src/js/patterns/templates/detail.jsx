import React, { Component, Fragment } from 'react'

import fixture from '../fixture'

import AtomsButton from '../atoms/buttons/button'
import AtomsIcon from '../atoms/icons/icon'
import AtomsPrice from '../atoms/prices/price'

import MoleculesImage from '../molecules/images/image'
import MoleculesSequentialNav from '../molecules/navigations/sequential-nav'

import OrganismsHead from '../organisms/base/head'
import OrganismsFoot from '../organisms/base/foot'

class TemplatesDetail extends Component {
    render () {
        return (<Fragment>
            <OrganismsHead />

            <main className='c-page-body    u-padding-top-none'>
                <div className='c-page-wrapper    o-wrapper'>
                    <div className='c-page-section    u-page-width@until-desk'>
                        <MoleculesImage icon='check' media={fixture.image.person} />
                    </div>

                    <div className='c-page-section'>
                        <h1 className='c-heading-gamma'>
                            <div className='c-heading-zeta'>
                                Ecko Unitd.
                            </div>
                            Jogginghose Swecko
                        </h1>
                    </div>

                    <div className='c-page-section'>
                        <ul className='c-price-list'>
                            <li className='c-price-list__item'><AtomsPrice className='u-text-l' highlight value={11599} /></li>
                            <li className='c-price-list__item'><span className='u-color-highlight'>30% sparen</span></li>
                            <li className='c-price-list__item'><AtomsPrice old value={14999} /></li>
                        </ul>
                    </div>

                    <div className='c-page-section'>
                        <form className='c-form' action=''>
                            <div className='c-form__item'>
                                <select className='c-form__select'>
                                    <option>Bitte Größe wählen</option>
                                    <option>XS</option>
                                    <option>S</option>
                                    <option>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                </select>
                            </div>

                            <div className='c-form__item'>
                                <AtomsButton type='primary' full>In den Warenkorb</AtomsButton>
                            </div>
                        </form>
                    </div>

                    <div className='c-page-section'>
                        <div className='s-text'>
                            <ul>
                                <li>lässige Jogginghose</li>
                                <li>Kordelzug innen am Saum</li>
                                <li>seitliche Einschubtasche</li>
                                <li>Dies ist ein Beispiel für ein etwas längeres List-Item</li>
                                <li>aufgesetzte Gesäßtasche</li>
                                <li>bequem geschnitten</li>
                            </ul>
                        </div>
                    </div>

                    <AtomsButton type='secondary' data-scroll component='a' href='#top' aria-label='Scroll to top of the page' className='c-scrollup'>
                        <span className='u-hidden-visually'>Nach oben</span>
                        <AtomsIcon icon='chevron-up' />
                    </AtomsButton>
                </div>

            </main>

            <MoleculesSequentialNav />

            <OrganismsFoot />
        </Fragment>)
    }
}

TemplatesDetail.propTypes = {
}

TemplatesDetail.defaultProps = {
}

export default TemplatesDetail
