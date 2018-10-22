import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsHeading from './10-heading'

class AtomsHeadings extends Component {
    render () {
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
