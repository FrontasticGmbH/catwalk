import React, { Component, Fragment } from 'react'

import fixture from '../fixture'

import MoleculesHero from '../20-molecules/30-heros/10-hero'
import MoleculesBlockTextImage from '../20-molecules/50-blocks/10-block-text-image'
import MoleculesSequentialNav from '../20-molecules/20-navigations/40-sequential-nav'

import OrganismsHead from '../30-organisms/10-base/10-head'
import OrganismsProductSlider from '../30-organisms/20-components/10-product-slider'
import OrganismsFoot from '../30-organisms/10-base/20-foot'

class TemplatesHome extends Component {
    render () {
        return (<Fragment>
            <OrganismsHead />
            <main className='c-page-body'>
                <div className='c-page-wrapper    o-wrapper'>
                    <section>
                        <div className='c-page-section'>
                            <header>
                                <h1 className='c-heading-alpha'>New Styles added!</h1>
                                <div className='c-heading-subtitle'>Jetzt den Sommer genießen</div>
                            </header>
                        </div>

                        <section className='c-page-section'>
                            <MoleculesHero media={fixture.image.person} />
                        </section>

                        <div className='c-page-section    u-page-width@until-desk    u-margin-left-minus@desk'>
                            <OrganismsProductSlider />
                        </div>
                    </section>

                    <section className='c-page-section'>
                        <h2 className='c-heading-gamma'>Genieße die beste Jahreszeit</h2>
                        <div className='o-layout'>
                            <div className='o-layout__item    u-1/2@lap'>
                                <MoleculesBlockTextImage media={fixture.image.person}>
                                    {fixture.excerpt.medium}
                                </MoleculesBlockTextImage>
                            </div>
                            <div className='o-layout__item    u-1/2@lap'>
                                <MoleculesBlockTextImage className='o-block--media-reverse@until-lap' media={fixture.image.person}>
                                    {fixture.excerpt.medium}
                                </MoleculesBlockTextImage>
                            </div>
                        </div>
                    </section>
                </div>
            </main>

            <MoleculesSequentialNav />

            <OrganismsFoot />
        </Fragment>)
    }
}

TemplatesHome.propTypes = {
}

TemplatesHome.defaultProps = {
}

export default TemplatesHome
