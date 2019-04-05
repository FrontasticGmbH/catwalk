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
        sfData.forEach(day => (day.date = new Date(day.date)))
        nyData.forEach(day => (day.date = new Date(day.date)))
        amData.forEach(day => (day.date = new Date(day.date)))
        this.setState({ temps: { sf: sfData, ny: nyData, am: amData } })
    }

    updateCity (event) {
        this.setState({ city: event.target.value })
    };

    render () {
        const data = this.state.temps[this.state.city]

        return (<div>
            <h1 className='s-text'>
                2017 Temperatures for{' '}
                <select name='city' onChange={(event) => this.updateCity(event)}>
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
