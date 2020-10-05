//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import LineChart from './visualisations/LineChart'
import BarChart from './visualisations/BarChart'
import RadialChart from './visualisations/RadialChart'

import sfData from './data/sf.json'
import nyData from './data/ny.json'
import amData from './data/am.json'

class DatavisCoded extends Component {
    state = {
        temps: {},
        city: 'ny',
    };

    componentDidMount () {
        sfData.forEach(day => { return (day.date = new Date(day.date)) })
        nyData.forEach(day => { return (day.date = new Date(day.date)) })
        amData.forEach(day => { return (day.date = new Date(day.date)) })
        this.setState({ temps: { sf: sfData, ny: nyData, am: amData } })
    }

    updateCity (event) {
        this.setState({ city: event.target.value })
    };

    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        const data = this.state.temps[this.state.city]

        return (<div>
            <h1 className='s-text'>
                2017 Temperatures for{' '}
                <select name='city' onChange={(event) => { return this.updateCity(event) }}>
                    {[
                        { label: 'San Francisco', value: 'sf' },
                        { label: 'New York', value: 'ny' },
                        { label: 'Amsterdam', value: 'am' },
                    ].map(option => {
                        return (
                            <option key={option.value} value={option.value}>
                                {option.label}
                            </option>
                        )
                    })}
                </select>
            </h1>
            <LineChart data={data} />
            <BarChart data={data} />
            <RadialChart data={data} />
        </div>)
    }
}

DatavisCoded.propTypes = {}

DatavisCoded.defaultProps = {}

export default DatavisCoded
