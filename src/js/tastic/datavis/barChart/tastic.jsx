//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {
    FlexibleWidthXYPlot,
    VerticalBarSeries,
    VerticalGridLines,
    HorizontalGridLines,
    XAxis,
    YAxis,
} from 'react-vis'
import 'react-vis/dist/style.css'
import exampleData from './exampleData.json'

const DEFAULTS = {
    // general chart options
    showXAxis: true,
    showYAxis: true,
    showVerticalGridLines: true,
    showHorizontalGridLines: true,

    // chart specific options
    chartHeight: 300,

    // styling options
    barFill: '#707070',
    barWidth: 0.5,

    // events
    onValueClick: () => { },

    // data
    data: exampleData,
}

class BarChart extends Component {
    getSettings () {
        return Object.assign({}, DEFAULTS, this.props.data || {})
    }

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        const settings = this.getSettings()

        return (
            <FlexibleWidthXYPlot height={settings.chartHeight}>

                {/* General Chart Options */}
                {settings.showXAxis ? <XAxis /> : null}
                {settings.showYAxis ? <YAxis /> : null}
                {settings.showVerticalGridLines ? <VerticalGridLines /> : null}
                {settings.showHorizontalGridLines ? <HorizontalGridLines /> : null}

                {/* Chart */}
                <VerticalBarSeries
                    data={settings.data}
                    barWidth={settings.barWidth}
                    fill={settings.barFill}
                    onValueClick={() => { return settings.onValueClick() }}
                />

            </FlexibleWidthXYPlot>
        )
    }
}

BarChart.propTypes = {
    data: PropTypes.object.isRequired,
}

BarChart.defaultProps = {}

export default BarChart
