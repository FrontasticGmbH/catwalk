import React, { Component, Fragment } from 'react'

import fixture from '../fixture'

import AtomsIcon from '../10-atoms/40-icons/10-icon'
import AtomsButton from '../10-atoms/10-buttons/10-button'

import MoleculesFilterBar from '../20-molecules/20-navigations/30-filter-bar'
import MoleculesSequentialNav from '../20-molecules/20-navigations/40-sequential-nav'
import MoleculesSequentialFilterNav from '../20-molecules/20-navigations/50-sequential-filter-nav'
import MoleculesHero from '../20-molecules/30-heros/10-hero'
import MoleculesProductTeaser from '../20-molecules/40-teasers/10-product-teaser'

import OrganismsHead from '../30-organisms/10-base/10-head'
import OrganismsFoot from '../30-organisms/10-base/20-foot'

class TemplatesCategoryOverview extends Component {
    render () {
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

                        <MoleculesHero media={fixture.image.person} caption={fixture.headline.short} />

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

                        <MoleculesHero media={fixture.image.person} caption={fixture.headline.short} size='small' />

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

TemplatesCategoryOverview.propTypes = {
}

TemplatesCategoryOverview.defaultProps = {
}

export default TemplatesCategoryOverview
