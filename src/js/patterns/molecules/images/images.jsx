//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import MoleculesImage from './image'

class MoleculesImages extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
