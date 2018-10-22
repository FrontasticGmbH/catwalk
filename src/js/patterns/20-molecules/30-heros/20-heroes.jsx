import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import MoleculesHero from './10-hero'

class MoleculesHeroes extends Component {
    render () {
        return (<Fragment>
            <MoleculesHero
                media={fixture.image.person}
                caption='Large Hero'
                size='large'
                ratio='3:1'
            />
            <MoleculesHero
                media={fixture.image.person}
                caption='Tiny Top Left Hero'
                verticalAlign='top'
                horizontalAlign='left'
                size='small'
                ratio='5:1'
            />
            <MoleculesHero
                media={fixture.image.person}
                caption='Hero with native aspect ratio'
            />
        </Fragment>)
    }
}

MoleculesHeroes.propTypes = {
}

MoleculesHeroes.defaultProps = {
}

export default ComponentInjector.return('MoleculesHeroes', MoleculesHeroes)
