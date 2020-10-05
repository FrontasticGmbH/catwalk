//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsPrice from '../prices/price'

class AtomsPrices extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<Fragment>
            <p><AtomsPrice value={2342} /></p>
            <p><AtomsPrice highlight value={2342} /></p>
            <p><AtomsPrice old value={2342} /></p>
            <p><AtomsPrice highlight old value={2342} /></p>
            <p><AtomsPrice value={-2342} /></p>
            <p><AtomsPrice forceSign value={2342} /></p>
        </Fragment>)
    }
}

AtomsPrices.propTypes = {
}

AtomsPrices.defaultProps = {
}

export default ComponentInjector.return('AtomsPrices', AtomsPrices)
