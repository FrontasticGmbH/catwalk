//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsHeading from './heading'

class AtomsHeadings extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<div>
            <AtomsHeading type='alpha'>Alpha Heading</AtomsHeading>
            <AtomsHeading type='beta'>Beta Heading</AtomsHeading>
            <AtomsHeading type='gamma'>Gamma Heading</AtomsHeading>
            <AtomsHeading type='delta'>Delta Heading</AtomsHeading>
            <AtomsHeading type='epsilon'>Epsilon Heading</AtomsHeading>
            <AtomsHeading type='zeta'>Zeta Heading</AtomsHeading>
            <AtomsHeading type='alpha' component='h2'>Alpha &lt;h2&gt; Heading</AtomsHeading>
        </div>)
    }
}

AtomsHeadings.propTypes = {
}

AtomsHeadings.defaultProps = {
}

export default ComponentInjector.return('AtomsHeadings', AtomsHeadings)
