//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component, Fragment } from 'react'

import fixture from '../fixture'

import AtomsIcon from '../atoms/icons/icon'
import AtomsButton from '../atoms/buttons/button'

import MoleculesFilterBar from '../molecules/navigations/filter-bar'
import MoleculesSequentialNav from '../molecules/navigations/sequential-nav'
import MoleculesSequentialFilterNav from '../molecules/navigations/sequential-filter-nav'
import MoleculesHero from '../molecules/heros/hero'
import MoleculesProductTeaser from '../molecules/teasers/product-teaser'

import OrganismsHead from '../organisms/base/head'
import OrganismsFoot from '../organisms/base/foot'

class TemplatesCategoryOverview extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<Fragment>
            <OrganismsHead />

            <main className='c-page-body'>
                <div className='c-page-wrapper    o-wrapper'>
                    <section className='c-page-section'>
                        <nav>
                            <AtomsButton size='large' ghost component='a' href='#'>
                                <AtomsIcon icon='chevron-left' iconSize='l' />
                                <span>Bekleidung</span>
                            </AtomsButton>
                        </nav>

                        <p>{fixture.excerpt.medium}</p>

                        <MoleculesFilterBar />

                        <MoleculesHero
                            media={{
                                media: fixture.image.person,
                                title: fixture.headline.short,
                            }}
                            caption={fixture.headline.short} />

                        <div className='o-layout'>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} />
                            </div>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                        </div>

                        <MoleculesHero
                            media={{
                                media: fixture.image.person,
                                title: fixture.headline.short,
                            }}
                            caption={fixture.headline.short}
                            size='small' />

                        <div className='o-layout'>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                            <div className='o-layout__item    u-1/2    u-1/3@lap    u-1/4@desk'>
                                <MoleculesProductTeaser product={fixture.product} showStrikePrice />
                            </div>
                        </div>

                        <AtomsButton type='secondary' full>Mehr Produkte laden</AtomsButton>
                    </section>
                </div>
            </main>

            <MoleculesSequentialNav />

            <MoleculesSequentialFilterNav />

            <OrganismsFoot />
        </Fragment>)
    }
}

TemplatesCategoryOverview.propTypes = {}

TemplatesCategoryOverview.defaultProps = {}

export default TemplatesCategoryOverview
