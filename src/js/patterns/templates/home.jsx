//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component, Fragment } from 'react'

import fixture from '../fixture'

import MoleculesHero from '../molecules/heros/hero'
import MoleculesBlockTextImage from '../molecules/blocks/block-text-image'
import MoleculesSequentialNav from '../molecules/navigations/sequential-nav'

import OrganismsHead from '../organisms/base/head'
import OrganismsProductSlider from '../organisms/components/product-slider'
import OrganismsFoot from '../organisms/base/foot'

class TemplatesHome extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<Fragment>
            <OrganismsHead />
            <main className='c-page-body'>
                <div className='c-page-wrapper o-wrapper'>
                    <section>
                        <div className='c-page-section'>
                            <header>
                                <h1 className='c-heading-alpha'>New Styles added!</h1>
                                <div className='c-heading-subtitle'>Jetzt den Sommer genießen</div>
                            </header>
                        </div>

                        <section className='c-page-section'>
                            <MoleculesHero media={{ media: fixture.image.person, title: fixture.headline.short }} />
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
