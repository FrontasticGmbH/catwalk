import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsPrice from '../prices/price'

class AtomsPrices extends Component {
    render () {
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
