import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import MoleculesImage from './10-image'

class MoleculesImages extends Component {
    render () {
        return <Fragment>
            <MoleculesImage media={fixture.image.person} />
            <MoleculesImage icon='check' ratio='1:1' media={fixture.image.person} />
            <MoleculesImage icon='cross' iconSize='s' ratio='5:1' media={fixture.image.person} />
        </Fragment>
    }
}

MoleculesImages.propTypes = {
}

MoleculesImages.defaultProps = {
}

export default ComponentInjector.return('MoleculesImages', MoleculesImages)
